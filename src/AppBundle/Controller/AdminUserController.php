<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\UserSearchForm;
use AppBundle\Form\UserEditForm;


class AdminUserController extends Controller
{

    /**
     * @Route("/dashboard/users", name="user-list")
     */
    public function userListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // Form for searching projections

        // Array of projections
        $users;

        $user = new User();
        $formSearch = $this->createForm(new UserSearchForm(), $user);

        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted()) {
            $users = $em->getRepository('AppBundle:User')
                ->findNameEmail(
                    $user->getUsername(),
                    $user->getEmail()
                );
        } else {
            // Retrieve all projections from db
            $users = $em->getRepository('AppBundle:User')->findNameEmail();
        }

        return $this->render('Admin/User/user-list.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'active' => 'users',
            'users' => $users,
            'formSearch' => $formSearch->createView(),
        ));
    }

    /**
     * @Route("/dashboard/users/edit/{id}", name="user-edit")
     */
    public function userEditAction (Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);

        // The user isn't in db
        if (!$user) {
            throw $this->createNotFoundException('User with name '
            .$id.' does not exist.');
        }

        // Variable for error message
        $errorMsg = '';

        // Edit form
        $form = $this->createForm(new UserEditForm(), $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Try delete user from db
            if ($form->get('delete')->isClicked()) {
                try {
                    $em->remove($user);
                    $em->flush();

                    return $this->redirectToRoute('user-list');
                } catch (\Doctrine\DBAL\DBALException $e) {
                    $errorMsg = 'Cannot delete the user due to integrity constraint violation.';
                }
            }

            // Save user into db
            if ($form->get('save')->isClicked()) {
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('user-edit', array(
                    'id' => $id,
                ));
            }
        }

        return $this->render('Admin/User/user-edit.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'active' => 'users',
            'form' => $form->createView(),
            'errorMsg' => $errorMsg,
        ));
    }

}
