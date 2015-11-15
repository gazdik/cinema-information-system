<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Projection;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\Seat;
use AppBundle\Entity\Form\UserForm;
use AppBundle\Form\FindUserForm;

class CashierSellTicketController extends Controller {
  /**
  *@Security("has_role('ROLE_CASHIER')")
  *@Route("/tickets/{action}/{user_id}/{ticket_id}",
  *       defaults={"action" = "display",
  *                 "user_id" = -1,
  *                 "ticket_id" = -1},
  *       name ="sellTicket")
  */
  public function displayTicketsAction(Request $request, $action, $user_id, $ticket_id) {

    $em = $this->getDoctrine()->getManager();
    $userManager = $this->container->get('fos_user.user_manager');
    $err = '';
    //object for retrieving user's mail
    $userForm = new UserForm();
    $user = null;
    $tickets = null;

    //reservation will be cancelled
    if ($action == 'cancel') {
      //get ticket
      $ticket = $em->getRepository('AppBundle:Ticket')
          ->find($ticket_id);

      $em->remove($ticket);
      $em->flush();

      //notify, that ticket has been removed
      $url = $this->generateUrl(
        'sellTicket',
        array(
          'user_id' => $user_id,
          'action' => 'cancelled',
        )
      );

      return $this->redirect($url);
    }

    //display a message, that the reservation has been cancelled
    if ($action == 'cancelled') {
      $request->getSession()->getFlashBag()
        ->add('success', 'Reservation has been successfully cancelled');
    }

    //ticket will be sold
    if ($action == 'sell') {
      $ticket = $em->getRepository('AppBundle:Ticket')
          ->find($ticket_id);

      $ticket->setPaymentDate(new \DateTime('now'));
      $em->flush();

      //notify, that ticket has been sold
      $url = $this->generateUrl(
        'sellTicket',
        array(
          'user_id' => $user_id,
          'action' => 'sold',
        )
      );

      return $this->redirect($url);
    }

    //display a message, that the ticket has been sold
    if ($action == 'sold') {
      $request->getSession()->getFlashBag()
        ->add('success', 'Ticket has been successfully sold');
    }

    //session is running, so get the user without resubmitting the form
    if ($user_id != -1) {
      $user = $userManager->findUserBy(array('id'=>$user_id));
    }

    $getUserForm = $this->createForm(new FindUserForm(), $userForm);
    $getUserForm->handleRequest($request);

    //get an user from the form
    if ($getUserForm->isSubmitted()) {
      $user = $userManager->findUserByEmail($userForm->getEmail());

      if (!$user) {
        $err = "No user found";
      }
    }

    //get user's tickets- only reservations
    $tickets = $em->createQueryBuilder()
      ->select('t')
      ->from('AppBundle:Ticket', 't')
      ->where('t.user = ?1')
      ->andWhere('t.payment_date IS NULL')
      ->setParameter(1, $user)
      ->getQuery()
      ->getResult();

    return $this->render('User/sellTicket.html.twig', array(
      'user' => $user,
      'getUserForm' => $getUserForm->createView(),
      'err' => $err,
      'tickets' => $tickets,
    ));
  }
}
