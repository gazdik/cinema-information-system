<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\PriceCategory;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\Form\Profit;

use AppBundle\Form\ProfitForm;
use AppBundle\Form\PriceCategoryEditForm;
use AppBundle\Form\TicketFindForm;
use AppBundle\Form\ReservationSellForm;
use AppBundle\Form\PriceCategoryAddForm;

/**
 * @Security("has_role('ROLE_MANAGER')")
 */
class AdminTicketController extends Controller
{

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
