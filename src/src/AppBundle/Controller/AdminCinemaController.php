<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Cinema;
use AppBundle\Entity\Hall;
use AppBundle\Form\CinemaEditForm;
use AppBundle\Form\CinemaAddForm;
use AppBundle\Form\HallAddForm;

/**
 * @Security("has_role('ROLE_MANAGER')")
 */
class AdminCinemaController extends Controller
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

        return $this->render('Admin/Cinema/cinema-list.html.twig', array(
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
        }

        // 'Add hall' form
        $newHall = new Hall();
        $formHallAdd = $this->createForm(new HallAddForm(), $newHall);
        $formHallAdd->handleRequest($request);

        if ($formHallAdd->isValid()) {
            $newHall->setCinema($cinema);
            $em->persist($newHall);
            $em->persist($cinema);
            $em->flush();

            return $this->redirectToRoute('cinema-edit', array(
                'cinema_name' => $cinema_name, ));
        }

        return $this->render('Admin/Cinema/cinema-edit.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'formCinema' => $formCinema->createView(),
            'formHallAdd' => $formHallAdd->createView(),
            'active' => 'cinemas',
            'cinema' => $cinema,
            'errorMsg' => $errorMsg,
        ));
    }
}
