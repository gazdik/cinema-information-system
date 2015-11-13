<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Cinema;
use AppBundle\Entity\Hall;
use AppBundle\Form\CinemaEditForm;
use AppBundle\Form\CinemaAddForm;
use AppBundle\Form\HallAddForm;
use AppBundle\Form\HallEditForm;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard/cinemas", name="cinema-list")
     */
    public function cinemaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $errorMsg = '';

        $newCinema = new Cinema();
        $form = $this->createForm(new CinemaAddForm(), $newCinema);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $em->persist($newCinema);
                $em->flush();

                return $this->redirectToRoute('cinema-list');
            } catch (\Doctrine\DBAL\DBALException $e) {
                $errorMsg = 'Cannot insert the cinema due to integrity constraint violation.';
            }
        }

        $cinemas = $em->getRepository('AppBundle:Cinema')->findAll();

        return $this->render('dashboard/cinemas/cinema-list.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'active' => 'cinemas',
            'cinemas' => $cinemas,
            'form' => $form->createView(),
            'errorMsg' => $errorMsg,
        ));
    }

    /**
     * @Route("/dashboard/cinemas/edit/{cinema_name}", name="cinema-edit")
     */
    public function cinemaEditAction(Request $request, $cinema_name)
    {
        $em = $this->getDoctrine()->getManager();

        $cinema = $em->getRepository('AppBundle:Cinema')->find($cinema_name);

        // Variable for error messages
        $errorMsg = '';

        // The cinema isn't in db
        if (!$cinema) {
            throw $this->createNotFoundException('Cinema with name '
            .$cinema_name.' does not exist.');
        }

        // 'Edit cinema' form
        $formCinema = $this->createForm(new CinemaEditForm(), $cinema);
        $formCinema->handleRequest($request);

        if ($formCinema->isSubmitted()) {
            if ($formCinema->get('delete')->isClicked()) {
                try {
                    $em->remove($cinema);
                    $em->flush();

                    return $this->redirectToRoute('cinema-list');
                } catch (\Doctrine\DBAL\DBALException $e) {
                    $errorMsg = 'Cannot delete the cinama due to integrity constraint violation.';
                }
            }

            if ($formCinema->get('save')->isClicked()) {
                $em->persist($cinema);
                $em->flush();
            }

            // return  $this->redirectToRoute('cinema-edit', array('cinema_name' => $cinema_name));
        }

        // 'Add hall' form
        $newHall = new Hall();
        $formHallAdd = $this->createForm(new HallAddForm(), $newHall);
        $formHallAdd->handleRequest($request);

        if ($formHallAdd->isValid()) {
            // $cinema->addHall($newHall);
            $newHall->setCinema($cinema);
            $em->persist($newHall);
            $em->persist($cinema);
            $em->flush();

            return $this->redirectToRoute('cinema-edit', array(
                'cinema_name' => $cinema_name, ));
        }

        return $this->render('dashboard/cinemas/cinema-edit.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'formCinema' => $formCinema->createView(),
            'formHallAdd' => $formHallAdd->createView(),
            'active' => 'cinemas',
            'cinema' => $cinema,
            'errorMsg' => $errorMsg,
        ));
    }

    /**
     * @Route("/dashbaord/halls/edit/{id}", name="hall-edit")
     */
    public function hallEditAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $hall = $em->getRepository('AppBundle:Hall')->find($id);

        // The hall isn't in db
        if (!$hall) {
            throw $this->createNotFoundException('Hall with id '
            .$id.' does not exist.');
        }

        $errorMsg = '';

        $form = $this->createForm(new HallEditForm(), $hall);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->get('delete')->isClicked()) {
                try {
                    $em->remove($hall);
                    $em->flush();

                    return $this->redirectToRoute('cinema-edit', array(
                        'cinema_name' => $hall->getCinema()->getName(),
                    ));
                } catch (\Exception $e) {
                    $errorMsg = 'Cannot delete the hall due to integrity constraint violation.';
                }
            }

            if ($form->get('save')->isClicked()) {
                $em->persist($hall);
                $em->flush();
            }
        }

        return $this->render('dashboard/halls/hall-edit.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'active' => 'cinemas',
            'form' => $form->createView(),
            'errorMsg' => $errorMsg,
        ));
    }
}
