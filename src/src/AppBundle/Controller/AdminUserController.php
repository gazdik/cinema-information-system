<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\UserSearchForm;
use AppBundle\Form\UserEditForm;
use AppBundle\Entity\Form\UserForm;


/**
 * @Security("has_role('ROLE_ADMIN')")
 */
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
        $userManager = $this->get('fos_user.user_manager');

        //get old user
        $user = $em->getRepository('AppBundle:User')->find($id);

        //create copy
        $newUser = $userManager->createUser();
        $newUser->setEmail($user->getEmail());
        $newUser->setName($user->getName());
        $newUser->setRoles($user->getRoles());
        $newUser->setEnabled($user->isEnabled());


        // The user isn't in db
        if (!$user) {
            throw $this->createNotFoundException('User with name '
            .$id.' does not exist.');
        }

        // Variable for error message
        $errorMsg = '';

        // Edit form
        $form = $this->createForm(new UserEditForm(), $newUser);
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
                try {
                    //chceck email
                    if ($newUser->getEmail() != $user->getEmail())
                        if ($userManager->findUserByEmail($user->getEmail()) == null) {
                            $user->setEmail($newUser->getEmail());
                        } else
                            throw new Exception('Sorry email address already taken.');

                    //save other values
                    $user->setName($newUser->getName());
                    $user->setRoles($newUser->getRoles());
                    $user->setEnabled($newUser->isEnabled());
                    $em->persist($user);
                    $em->flush();
                } catch(Exception $e) {
                    $request->getSession()->getFlashBag()
                        ->add('warning', $e->getMessage());
                }

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
