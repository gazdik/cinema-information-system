<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Projection;
use AppBundle\Entity\Form\SearchProjections;
use AppBundle\Form\SearchProjectionsForm;

class UserProgrammeController extends Controller
{
    /**
     * @Route("/programme", name="programme")
     */
    public function programmeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // Array for arrays of projections
        $projections;

        // Create object to store form result
        $search = new SearchProjections();
        $form = $this->createForm(new SearchProjectionsForm(), $search);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $projections = $em->getRepository('AppBundle:Projection')
                ->awesomeFind(
                    $search->getMovie(),
                    $search->getCinema(),
                    $search->getDate(),
                    $search->getGenre());
        } else {

            // Get projections for 1 days
            $date_to = new \DateTime();
            $date_to->modify('+5 days');
            $projections = $em->getRepository('AppBundle:Projection')
                ->findFromToOrderer(new \DateTime(), $date_to);
        }

        return $this->render('User/programme.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'projections' => $projections,
            'form' => $form->createView(),
        ));
    }
}
