<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Projection;
use AppBundle\Entity\Form\SearchProjections;
use AppBundle\Form\SearchProjectionsForm;


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
            'projections' => $projections,
        ));
    }

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
            // // Get projections for 3 days
            // $begin = new \DateTime();
            // $end = new \DateTime();
            // $end->modify('+3 days');
            // $interval = \DateInterval::createFromDateString('1 day');
            // $period = new \DatePeriod($begin, $interval, $end);
            //
            // foreach ($period as $dt) {
            //     $result = $em->getRepository('AppBundle:Projection')
            //         ->findByDateOrdered($dt);
            //
            //     array_push($projections, $result);
            // }

            // Get projections for 1 day
            $date_to = new \DateTime();
            $date_to->modify('+1 days');
            $projections = $em->getRepository('AppBundle:Projection')
                ->findFromToOrderer(new \DateTime(), $date_to);
        }

        return $this->render('programme.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'projections' => $projections,
            'form' => $form->createView(),
        ));
    }

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
