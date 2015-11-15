<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Movie;
use AppBundle\Entity\MovieGenre;
use AppBundle\Form\MovieSearchForm;
use AppBundle\Form\MovieEditForm;
use AppBundle\Form\MovieAddForm;
use AppBundle\Form\GenreAddForm;
use AppBundle\Form\GenreEditForm;

/**
 * @Security("has_role('ROLE_MANAGER')")
 */
class AdminMovieController extends Controller
{
    /**
     * @Route("/dashboard/movies", name="movie-list")
     */
    public function movieListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $errorMsg = '';

        // Form for adding a movie
        $newMovie = new Movie();
        $formAdd = $this->createForm(new MovieAddForm(), $newMovie);
        $formAdd->handleRequest($request);

        if ($formAdd->isValid()) {
            try {
                $em->persist($newMovie);
                $em->flush();

                return $this->redirectToRoute('movie-list');
            } catch (\Doctrine\DBAL\DBALException $e) {
                $errorMsg = 'Cannot insert the movie due to integrity constraint violation.';
            }
        }

        // Form for searching movies

        // Array for arrays of movie-list
        $movies;

        $search = new Movie();
        $formSearch = $this->createForm(new MovieSearchForm(), $search);

        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted()) {
            $movies = $em->getRepository('AppBundle:Movie')
                ->awesomeFind(
                    $search->getName(),
                    $search->getYear(),
                    $search->getLength(),
                    $search->getGenre()
                );
        } else {
            // Retrieve all movies from db
            $movies = $em->getRepository('AppBundle:Movie')->findOrdered();
        }

        return $this->render('Admin/Movie/movie-list.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'active' => 'movies',
            'movies' => $movies,
            'formAdd' => $formAdd->createView(),
            'formSearch' => $formSearch->createView(),
            'errorMsg' => $errorMsg,
        ));
    }

    /**
     * @Route("/dashboard/movies/edit/{id}", name="movie-edit")
     */
    public function movieEditAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository('AppBundle:Movie')->find($id);

        // The movie isn't in db
        if (!$movie) {
            throw $this->createNotFoundException('Movie with id '
            .$id.' does not exist.');
        }

        // Variable for error message
        $errorMsg = '';

        $form = $this->createForm(new MovieEditForm(), $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Try delete movie from db
            if ($form->get('delete')->isClicked()) {
                try {
                    $em->remove($movie);
                    $em->flush();

                    return $this->redirectToRoute('movie-list');
                } catch (\Doctrine\DBAL\DBALException $e) {
                    $errorMsg = 'Cannot delete the movie due to integrity constraint violation.';
                }
            }

            // Save movie into db
            if ($form->get('save')->isClicked()) {
                $em->persist($movie);
                $em->flush();
            }
        }

        return $this->render('Admin/Movie/movie-edit.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'active' => 'movies',
            'form' => $form->createView(),
            'errorMsg' => $errorMsg,
        ));
    }

    /**
     * @Route("/dashboard/movies/genres", name="genre-list")
     */
    public function genreListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $errorMsg = '';

        // Form for adding a genre
        $newGenre = new MovieGenre();
        $formAdd = $this->createForm(new GenreAddForm(), $newGenre);
        $formAdd->handleRequest($request);

        if ($formAdd->isValid()) {
            try {
                $em->persist($newGenre);
                $em->flush();

                return $this->redirectToRoute('genre-list');
            } catch (\Doctrine\DBAL\DBALException $e) {
                $errorMsg = 'Cannot insert the genre due to integrity constraint violation.';
            }
        }

        // Retrieve genres from db
        $genres = $em->getRepository('AppBundle:MovieGenre')->findAll();

        return $this->render('Admin/Movie/genre-list.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'active' => 'genres',
            'genres' => $genres,
            'formAdd' => $formAdd->createView(),
            'errorMsg' => $errorMsg,
        ));
    }
    /**
     * @Route("/dashboard/movies/genres/edit/{genre_name}", name="genre-edit")
     */
    public function genreEditAction(Request $request, $genre_name)
    {
        $em = $this->getDoctrine()->getManager();
        $genre = $em->getRepository('AppBundle:MovieGenre')->find($genre_name);

        // The genre isn't in db
        if (!$genre) {
            throw $this->createNotFoundException('Genre with name '
            .$id.' does not exist.');
        }

        // Variable for error message
        $errorMsg = '';


        // Temp genre
        $genreEdited = new MovieGenre();
        $genreEdited = $genre;

        // Edit form
        $form = $this->createForm(new GenreEditForm(), $genreEdited);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Try delete genre from db
            if ($form->get('delete')->isClicked()) {
                try {
                    $em->remove($genre);
                    $em->flush();

                    return $this->redirectToRoute('genre-list');
                } catch (\Doctrine\DBAL\DBALException $e) {
                    $errorMsg = 'Cannot delete the genre due to integrity constraint violation.';
                }
            }

            // Save genre into db
            if ($form->get('save')->isClicked()) {
                try {
                    $em->remove($genre);

                    $em->persist($genreEdited);

                    $em->flush();

                    return $this->redirectToRoute('genre-edit', array(
                        'genre_name' => $genreEdited->getGenre()));
                } catch (\Doctrine\DBAL\DBALException $e) {
                    $errorMsg = 'Cannot rename the genre due to integrity constraint violation.';
                }
            }
        }

        return $this->render('Admin/Movie/genre-edit.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'active' => 'genres',
            'form' => $form->createView(),
            'errorMsg' => $errorMsg,
        ));
    }
}
