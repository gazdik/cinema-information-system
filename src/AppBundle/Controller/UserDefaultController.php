<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        return $this->redirect('/programme');
        /*
        $em = $this->getDoctrine()->getManager();
        $projections = $em->getRepository('AppBundle:Projection')
          ->findByDateOrdered(new \DateTime());

        return $this->render('User/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'projections' => $projections,
        ));*/
    }
}
