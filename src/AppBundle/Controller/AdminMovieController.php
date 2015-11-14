<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Movie;
use AppBundle\Form\MovieSearchForm;
use AppBundle\Form\MovieEditForm;
use AppBundle\Form\MovieAddForm;

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
            // Try delete hall from db
            if ($form->get('delete')->isClicked()) {
                try {
                    $em->remove($movie);
                    $em->flush();

                    return $this->redirectToRoute('movie-list');
                } catch (\Doctrine\DBAL\DBALException $e) {
                    $errorMsg = 'Cannot delete the movie due to integrity constraint violation.';
                }
            }

            // Save hall into db
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
}
