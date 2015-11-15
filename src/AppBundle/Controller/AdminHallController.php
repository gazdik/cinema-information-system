<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Hall;
use AppBundle\Form\HallEditForm;

/**
 * @Security("has_role('ROLE_MANAGER')")
 */
class AdminHallController extends Controller
{
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

        // Variable for error message
        $errorMsg = '';

        $form = $this->createForm(new HallEditForm(), $hall);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Try delete hall from db
            if ($form->get('delete')->isClicked()) {
                try {
                    $em->remove($hall);
                    $em->flush();

                    return $this->redirectToRoute('cinema-edit', array(
                        'cinema_name' => $hall->getCinema()->getName(),
                    ));
                } catch (\Doctrine\DBAL\DBALException $e) {
                    $errorMsg = 'Cannot delete the hall due to integrity constraint violation.';
                }
            }

            // Save hall into db
            if ($form->get('save')->isClicked()) {
                $em->persist($hall);
                $em->flush();
            }
        }

        return $this->render('Admin/Hall/hall-edit.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'active' => 'cinemas',
            'form' => $form->createView(),
            'errorMsg' => $errorMsg,
        ));
    }
}
