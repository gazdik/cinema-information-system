<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Projection;

class ProgrammeController extends Controller
{
    /**
     * @Route("/programme", name="programme")
     */
    public function programmeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // Array for arrays of projections
        $proj_days = array();

        $begin = new \DateTime();
        $end = new \DateTime();
        $end->modify('+3 days');
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            $projections = $em->getRepository('AppBundle:Projection')
              ->findByDateOrdered($dt);

            array_push($proj_days, $projections);
        }

        // replace this example code with whatever you need
        return $this->render('programme.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'proj_days' => $proj_days,
        ));
    }
}
