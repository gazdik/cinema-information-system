<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProgrammeController extends Controller
{
    /**
     * @Route("/programme", name="programme")
     */
    public function programmeAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('programme.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
}
