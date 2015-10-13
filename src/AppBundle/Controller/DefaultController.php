<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Hall;
use AppBundle\Entity\Projection;
use AppBundle\Entity\Movie;
use AppBundle\Entity\Cinema;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $projections = $em->getRepository('AppBundle:Projection')
          ->findByDateOrdered(new \DateTime());

        return $this->render('index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'projections' => $projections
        ));
    }
}
