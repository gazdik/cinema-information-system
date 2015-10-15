<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Projection;
use AppBundle\Entity\SearchProjections;

class ProgrammeController extends Controller
{
    /**
     * @Route("/programme/{date}", defaults={"date" = 1}, name="programme")
     */
    public function programmeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // Array for arrays of projections
        $proj_days = array();

        // Create SearchProjections object
        $search = new SearchProjections();
        $form = $this->createFormBuilder($search)
            ->add('movie', 'search', array(
                'required' => false,
            ))
            ->add('date', 'collot_datetime', array(
                'pickerOptions' => array('minView' => 'month',
                    'viewSelect' => 'month',
                    'autoclose' => true,
                    'format' => 'dd/mm/yyyy', ),
                'required' => false))
            ->add('cinema', 'entity', array(
                'class' => 'AppBundle:Cinema',
                'empty_data'  => null,
                'required' => false,
                'placeholder' => 'Kino',
                'choice_label' => 'name', ))
            ->add('genre', 'entity', array(
                'class' => 'AppBundle:MovieGenre',
                'empty_data'  => null,
                'required' => false,
                'placeholder' => 'Žáner',
                'choice_label' => 'genre', ))
            ->add('save', 'submit', array('label' => 'Vyhľadaj'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
        } else {
            // Get projections for 3 days
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
        }

        return $this->render('programme.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'proj_days' => $proj_days,
            'form' => $form->createView(),
        ));
    }
}
