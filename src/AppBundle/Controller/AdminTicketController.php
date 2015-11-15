<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\PriceCategory;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\Form\Profit;
use AppBundle\Entity\Form\SearchProjections;
use AppBundle\Form\TicketSellForm;
use AppBundle\Form\ProfitForm;
use AppBundle\Form\PriceCategoryEditForm;
use AppBundle\Form\SearchProjectionsForm;
use AppBundle\Form\TicketFindForm;
use AppBundle\Form\ReservationSellForm;
use AppBundle\Form\PriceCategoryAddForm;

class AdminTicketController extends Controller
{
    /**
     * @Route("/dashboard/cashier", name="cashier")
     */
    public function cashierAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $errorMsg = '';

        // Form for selling reserved ticket
        $ticket = new Ticket();
        $formSell = $this->createForm(new TicketFindForm(), $ticket);
        $formSell->handleRequest($request);

        if ($formSell->isValid()) {
            $ticket = $em->getRepository('AppBundle:Ticket')->findReservation($ticket->getId());

            if (!$ticket) {
                $errorMsg = 'Reservation doesn\'t exist.';
            } else {
                return $this->redirectToRoute('reservation-sell', array(
                    'id' => $ticket->getId(),
                ));
            }
        }

        // Form for searching projections

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

        return $this->render('Admin/Ticket/cashier.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'active' => 'cashier',
            'projections' => $projections,
            'formSell' => $formSell->createView(),
            'formSearch' => $formSearch->createView(),
            'errorMsg' => $errorMsg,
        ));
    }

    /**
     * @Route("/dashboard/cashier/reservation-sell/{id}", name="reservation-sell")
     */
    public function reservationSellAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $ticket = $em->getRepository('AppBundle:Ticket')->find($id);

        // The ticket isn't in db
        if (!$ticket) {
            throw $this->createNotFoundException('Ticket with id '
            .$id.' does not exist.');
        }

        // Variable for error message
        $errorMsg = '';

        $form = $this->createForm(new ReservationSellForm(), $ticket);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // Sell ticket
            $ticket->setPaymentDate(new \DateTime());

            $em->persist($ticket);
            $em->flush();

            return $this->redirectToRoute('cashier');
        }

        return $this->render('Admin/Ticket/reservation-sell.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'active' => 'cashier',
            'ticket' => $ticket,
            'form' => $form->createView(),
            'errorMsg' => $errorMsg,
        ));
    }

    /**
     * @Route("/dashboard/cashier/ticket-sell/{projectionId}", name="ticket-sell")
     */
    public function ticketSellAction(Request $request, $projectionId)
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
            // TODO: set username of cashier
            $ticket->setProjection($projection)
                ->setPaymentDate(new \DateTime())
                ->setTicketPrice($ticket->getPriceCategory()->getCategoryPrice());

            $em->persist($ticket);
            $em->flush();

          // redirect to reservations page
          return $this->redirectToRoute('cashier');
        }

        return $this->render('Admin/Ticket/ticket-sell.html.twig', array(
          'projection' => $projection,
          'active' => 'cashier',
          'errorMsg' => $errorMsg,
          'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/dashboard/tickets/categories", name="category-list")
     */
    public function categoryListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $errorMsg = '';

        // Form for adding a price category
        $category = new PriceCategory();
        $formAdd = $this->createForm(new PriceCategoryAddForm(), $category);
        $formAdd->handleRequest($request);

        if ($formAdd->isValid()) {
            try {
                $em->persist($category);
                $em->flush();

                return $this->redirectToRoute('category-list');
            } catch (\Doctrine\DBAL\DBALException $e) {
                $errorMsg = 'Cannot insert the price category due to integrity constraint violation.';
            }
        }

        // Retrieve categories from db
        $categories = $em->getRepository('AppBundle:PriceCategory')->findAll();

        return $this->render('Admin/Ticket/category-list.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'active' => 'categories',
            'categories' => $categories,
            'formAdd' => $formAdd->createView(),
            'errorMsg' => $errorMsg,
        ));
    }
    /**
     * @Route("/dashboard/tickets/categories/edit/{category_name}", name="category-edit")
     */
    public function categoryEditAction(Request $request, $category_name)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:PriceCategory')->find($category_name);

        // The price category isn't in db
        if (!$category) {
            throw $this->createNotFoundException('Price category with name '
            .$category_name.' does not exist.');
        }

        // Variable for error message
        $errorMsg = '';

        // Edit form
        $form = $this->createForm(new PriceCategoryEditForm(), $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Try delete genre from db
            if ($form->get('delete')->isClicked()) {
                try {
                    $em->remove($category);
                    $em->flush();

                    return $this->redirectToRoute('category-list');
                } catch (\Doctrine\DBAL\DBALException $e) {
                    $errorMsg = 'Cannot delete the price category due to integrity constraint violation.';
                }
            }

            // Save genre into db
            if ($form->get('save')->isClicked()) {
                $em->persist($category);
                $em->flush();

                return $this->redirectToRoute('category-edit', array(
                    'category_name' => $category_name, ));
            }
        }

        return $this->render('Admin/Ticket/category-edit.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'active' => 'categories',
            'form' => $form->createView(),
            'errorMsg' => $errorMsg,
        ));
    }


    /**
     * @Route("/dashboard/profit", name="profit")
     */
    public function profitAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // Variable for error message
        $errorMsg = '';

        $profit = new Profit();
        $form = $this->createForm(new ProfitForm(), $profit);
        $form->handleRequest($request);

        if ($form->get('show')->isClicked()) {
            $errorMsg = '';

            // if it's really valid
            if ($profit->getMovie() or !$profit->getCinemas()->isEmpty()) {
                // retrieve tickets from db
                $tickets = $em->getRepository('AppBundle:Ticket')
                    ->findSellTickets($profit->getMovie(), $profit->getCinemas());


                $sum = 0;
                foreach ($tickets as $ticket) {
                    $sum += $ticket->getTicketPrice();
                }

                $profit->setValid(True);
                $profit->setValue($sum);
            } else {
                $errorMsg = 'Choose at least one option!';
            }
        }

        if ($form->get('clear')->isClicked()) {
            return $this->redirectToRoute('profit');
        }


        return $this->render('Admin/Ticket/profit.html.twig', array(
          'profit' => $profit,
          'active' => 'profit',
          'errorMsg' => $errorMsg,
          'form' => $form->createView(),
        ));
    }
}
