<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Projection;
use AppBundle\Entity\Form\SearchProjections;
use AppBundle\Form\SearchProjectionsForm;
use AppBundle\Entity\Ticket;
use AppBundle\Form\BookProjectionForm;
use AppBundle\Entity\Seat;

class ProgrammeController extends Controller
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

            // Get projections for 5 days
            $date_to = new \DateTime();
            $date_to->modify('+5 days');
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
    *@Security("has_role('ROLE_USER')")
    *@Route("/programme/booking/{projection_id}", name ="booking")
    */
    public function bookingAction(Request $request, $projection_id) {

      $em = $this->getDoctrine()->getManager();

      //retrieve a projection from the database
      $projection = $em
        ->getRepository('AppBundle:Projection')
        ->find($projection_id);

      //all seats available for the projection
      $allSeats = $projection->getHall()->getSeats()->toArray();

      //get reserved tickets for the projection
      $reservedTickets = $em->createQueryBuilder()
        ->select('t')
        ->from('AppBundle:Ticket', 't')
        ->where('t.projection = ?1')
        ->setParameter(1, $projection)
        ->getQuery()
        ->getResult();

      //get reserved seats for the projection
      $reservedSeats = array();
      foreach ($reservedTickets as $ticket) {
        $reservedSeats[] = $ticket->getSeat();
      }

      // get free seats
      $freeSeats = array_udiff($allSeats, $reservedSeats,
        function ($hallSeat, $reservedSeat) {
          return $hallSeat->getId() - $reservedSeat->getId();
        });

      //a ticket which will be stored
      $ticket = new Ticket();

      $form = $this->createForm(new BookProjectionForm($freeSeats), $ticket);
      $form->handleRequest($request);

      if($form->isValid()) {
        //set the other fields
        $ticket->setUser($this->getUser())
               ->setProjection($projection)
               ->setTicketPrice($ticket->getPriceCategory()->getCategoryPrice())
               ->setBookingDate(new \DateTime('now'));
        $em->persist($ticket);
        $em->flush();

        //TODO: the route needs to be changed
        return $this->redirectToRoute('homepage');
      }

      return $this->render('booking.html.twig', array(
        'projection' => $projection,
        'form' => $form->createView(),
      ));
    }
}
