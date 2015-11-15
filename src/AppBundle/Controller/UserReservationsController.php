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

class UserReservationsController extends Controller {

  /**
  *@Security("has_role('ROLE_USER')")
  *@Route("/reservations", name ="reservations")
  */
  public function displayReservationsAction(Request $request) {

    $em = $this->getDoctrine()->getManager();

    //get reserved tickets
    $reservedTickets = $em->createQueryBuilder()
      ->select('t')
      ->from('AppBundle:Ticket', 't')
      ->where('t.user = ?1')
      ->setParameter(1, $this->getUser())
      ->getQuery()
      ->getResult();

      return $this->render('User/reservations.html.twig', array(
        'tickets' => $reservedTickets,
      ));

  }

  /**
  *@Security("has_role('ROLE_USER')")
  *@Route("/reservations/{ticket_id}", name ="removeReservation")
  */
  public function removeReservationAction(Request $request, $ticket_id) {

    $em = $this->getDoctrine()->getManager();

    //get ticket
    $ticket = $em->getRepository('AppBundle:Ticket')
        ->find($ticket_id);

    //remove ticket
    $em->remove($ticket);
    $em->flush();

    return $this->redirectToRoute('reservations');
  }
}
