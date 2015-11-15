<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Projection;
use AppBundle\Entity\Movie;
use AppBundle\Entity\Form\SearchProjections;
use AppBundle\Form\SearchProjectionsForm;
use AppBundle\Form\ProjectionEditForm;
use AppBundle\Form\ProjectionAddForm;

/**
 * @Security("has_role('ROLE_MANAGER')")
 */
class AdminProjectionController extends Controller
{
    /**
     * @Route("/dashboard/projections", name="projection-list")
     */
    public function projectionListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $errorMsg = '';

        // Form for adding a movie
        $newProjection = new Projection();
        $formAdd = $this->createForm(new ProjectionAddForm(), $newProjection);
        $formAdd->handleRequest($request);

        if ($formAdd->isValid()) {
            try {
                $em->persist($newProjection);
                $em->flush();

                return $this->redirectToRoute('projection-list');
            } catch (\Doctrine\DBAL\DBALException $e) {
                $errorMsg = 'Cannot insert the projection due to integrity constraint violation.';
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

        return $this->render('Admin/Projection/projection-list.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'active' => 'projections',
            'projections' => $projections,
            'formAdd' => $formAdd->createView(),
            'formSearch' => $formSearch->createView(),
            'errorMsg' => $errorMsg,
        ));
    }

    /**
     * @Route("/dashboard/projections/edit/{id}", name="projection-edit")
     */
    public function projectionEditAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $projection = $em->getRepository('AppBundle:Projection')->find($id);

        // The projection isn't in db
        if (!$projection) {
            throw $this->createNotFoundException('Movie with id '
            .$id.' does not exist.');
        }

        // Variable for error message
        $errorMsg = '';

        $form = $this->createForm(new ProjectionEditForm(), $projection);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Try delete projection from db
            if ($form->get('delete')->isClicked()) {
                try {
                    $em->remove($projection);
                    $em->flush();

                    return $this->redirectToRoute('projection-list');
                } catch (\Doctrine\DBAL\DBALException $e) {
                    $errorMsg = 'Cannot delete the movie due to integrity constraint violation.';
                }
            }

            // Save projection into db
            if ($form->get('save')->isClicked()) {
                $em->persist($projection);
                $em->flush();
            }
        }

        return $this->render('Admin/Projection/projection-edit.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'active' => 'projections',
            'projection' => $projection,
            'form' => $form->createView(),
            'errorMsg' => $errorMsg,
        ));
    }



}
