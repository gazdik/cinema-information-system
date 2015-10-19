<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CinemasController extends Controller
{
    /**
     * @Route("/cinemas", name="cinemas")
     */
    public function cinemasAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $cinemas = $em->getRepository('AppBundle:Cinema')->findAll();

        return $this->render('cinemas.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'cinemas' => $cinemas,
        ));
    }
}
