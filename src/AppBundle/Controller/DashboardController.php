<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Cinema;
use AppBundle\Form\CinemaEditForm;
use AppBundle\Form\CinemaAddForm;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard/cinemas", name="cinema-list")
     */
    public function cinemaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $newCinema = new Cinema();
        $form = $this->createForm(new CinemaAddForm(), $newCinema);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($newCinema);
            $em->flush();

            return $this->redirectToRoute('cinema-list');
        }

        $cinemas = $em->getRepository('AppBundle:Cinema')->findAll();

        return $this->render('dashboard/cinemas/cinema-list.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'active' => 'cinemas',
            'cinemas' => $cinemas,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/dashboard/cinemas/edit/{cinema_name}", name="cinema-edit")
     */
    public function cinemaEditAction(Request $request, $cinema_name)
    {
        $em = $this->getDoctrine()->getManager();

        $cinema = $em->getRepository('AppBundle:Cinema')->find($cinema_name);

        if (!$cinema) {
            throw $this->createNotFoundException('Cinema with name '
            .$cinema_name.' does not exist.');
        }

        $form = $this->createForm(new CinemaEditForm(), $cinema);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($cinema);
            $em->flush();

            return  $this->redirectToRoute('cinema-edit', array('cinema_name' => $cinema_name));
        }

        return $this->render('dashboard/cinemas/cinema-edit.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'form' => $form->createView(),
            'active' => 'cinemas',
            'cinema' => $cinema,
        ));
    }

    /**
     * @Route("/dashbaord/cinemas/delete/{cinema_name}", name="cinema-delete")
     */
    public function cinemaDeleteAction(Request $request, $cinema_name)
    {
        $em = $this->getDoctrine()->getManager();

        $cinema = $em->getRepository('AppBundle:Cinema')->find($cinema_name);

        $em->remove($cinema);

        try {
            $em->flush($cinema);
        }
        catch (\Exception $e) {
            return $this->redirectToRoute('cinema-list');
        }

        return $this->redirectToRoute('cinema-list');
    }
}
