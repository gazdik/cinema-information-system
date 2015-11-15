<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Projection;
use AppBundle\Entity\Form\SearchProjections;
use AppBundle\Form\SearchProjectionsForm;


class UserDefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $projections = $em->getRepository('AppBundle:Projection')
          ->findByDateOrdered(new \DateTime());

        return $this->render('User/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'projections' => $projections,
        ));
    }

    /**
     * @Route("/query/movie-search", name="movie-search")
     */
    public function searchMovieAction(Request $request)
    {
        $q = $request->get('term');
        $em = $this->getDoctrine()->getManager();
        $movies = $em->getRepository('AppBundle:Movie')->findLikeName($q);

        $results = array();
        foreach ($movies as $movie) {
            $results[] = array(
                'id' => $movie->getId(),
                'label' => $movie->getName(),
                'value' => $movie->getName()
            );
        }

        return new JsonResponse($results);
    }

    /**
     * @Route("/query/movie-get/{id}", name="movie-get")
     */
    public function getMovieAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository('AppBundle:Movie')->find($id);

        return new Response($movie->getName());
    }
}
