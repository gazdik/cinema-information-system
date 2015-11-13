<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
// TODO: delete
use AppBundle\Entity\Cinema;

use AppBundle\Entity\Movie;
// use AppBundle\Form\MovieSearchForm;
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

        // Get first 20 movies from db
        $movies = $em->getRepository('AppBundle:Movie')->findOrdered(20);

        return $this->render('Admin/Movie/movie-list.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'active' => 'movies',
            'movies' => $movies,
            'formAdd' => $formAdd->createView(),
            'errorMsg' => $errorMsg,
        ));
    }

    /**
     * @Route("/dashboard/movies/edit/{$id}", name="movie-edit")
     */
    public function movieEditAction(Request $request, $id)
    {

    }
}
