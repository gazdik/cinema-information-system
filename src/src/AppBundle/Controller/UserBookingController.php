<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Projection;
use AppBundle\Entity\Ticket;
use AppBundle\Form\BookProjectionForm;
use AppBundle\Entity\Seat;

class UserBookingController extends Controller
{
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

        // redirect to reservations page
        return $this->redirectToRoute('reservations');
      }

      return $this->render('User/booking.html.twig', array(
        'projection' => $projection,
        'form' => $form->createView(),
      ));
    }
}
