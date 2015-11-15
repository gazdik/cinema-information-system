<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Projection;
use AppBundle\Entity\Form\SearchProjections;
use AppBundle\Form\SearchProjectionsForm;
use AppBundle\Form\TicketSellForm;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\Seat;
use AppBundle\Entity\Form\UserForm;
use AppBundle\Form\FindUserForm;

class AdminCashierController extends Controller {

  /**
   * @Route("/dashboard/cashier", name="cashier")
   */
  public function cashierAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $errorMsg = '';

      return $this->render('Admin/Ticket/cashier.html.twig', array(
          'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
          'active' => 'cashier',
      ));
  }


  /**
   * @Route("/dashboard/cashier/tickets", name="cashier-tickets")
   */
  public function cashierTicketsAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $errorMsg = '';

      // Form for searching projections:
      // Array of projections
      $projections;

      $search = new SearchProjections();
      $formSearch = $this->createForm(new SearchProjectionsForm(), $search);

      $formSearch->handleRequest($request);

      if ($formSearch->isSubmitted()) {
          $projections = $em->getRepository('AppBundle:Projection')
              ->awesomeFind(
                  $search->getMovie(),
                  $search->getCinema(),
                  $search->getDate(),
                  $search->getGenre()
              );
      } else {
          // Retrieve all projections from db
          $projections = $em->getRepository('AppBundle:Projection')->findAllOrdered();
      }

      return $this->render('Admin/Ticket/cashier-tickets.html.twig', array(
          'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
          'active' => 'cashier-tickets',
          'projections' => $projections,
          'formSearch' => $formSearch->createView(),
          'errorMsg' => $errorMsg,
      ));
  }

  /**
   * @Route("/dashboard/cashier/tickets/sell/{projectionId}", name="cashier-ticketsell")
   */
  public function cashierTicketSellAction(Request $request, $projectionId)
  {
      $em = $this->getDoctrine()->getManager();

      // Variable for error message
      $errorMsg = '';

      //retrieve a projection from the database
      $projection = $em
        ->getRepository('AppBundle:Projection')
        ->find($projectionId);

        // The projection isn't in db
        if (!$projection) {
            throw $this->createNotFoundException('Projection with id '
            .$projectionId.' does not exist.');
        }

      // get free seats
      $freeSeats = $em->getRepository('AppBundle:Seat')
          ->findFreeSeats($projectionId);

      //a ticket which will be stored
      $ticket = new Ticket();

      $form = $this->createForm(new TicketSellForm($freeSeats), $ticket);
      $form->handleRequest($request);

      if ($form->isValid()) {
          //set the other fields
          $ticket->setProjection($projection)
              ->setPaymentDate(new \DateTime())
              ->setTicketPrice($ticket->getPriceCategory()->getCategoryPrice());

          $em->persist($ticket);
          $em->flush();

        // redirect to reservations page
        return $this->redirectToRoute('cashier');
      }

      return $this->render('Admin/Ticket/cashier-ticketsell.html.twig', array(
        'projection' => $projection,
        'active' => 'cashier-tickets',
        'errorMsg' => $errorMsg,
        'form' => $form->createView(),
      ));
  }


  /**
  *@Security("has_role('ROLE_CASHIER')")
  *@Route("/dashboard/cashier/reservations/{action}/{user_id}/{ticket_id}",
  *       defaults={"action" = "display",
  *                 "user_id" = -1,
  *                 "ticket_id" = -1},
  *       name ="cashier-reservations")
  */
  public function cashierReservationsController (Request $request, $action, $user_id, $ticket_id) {

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
        'cashier-reservations',
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
        'cashier-reservations',
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

    return $this->render('Admin/Ticket/cashier-reservations.html.twig', array(
      'active' => 'cashier-reservations',
      'user' => $user,
      'getUserForm' => $getUserForm->createView(),
      'err' => $err,
      'tickets' => $tickets,
    ));
  }
}
