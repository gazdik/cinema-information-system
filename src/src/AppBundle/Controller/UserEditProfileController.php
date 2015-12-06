<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Form\UserForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\EditProfileForm;

class UserEditProfileController extends Controller {
    /**
     *@Security("has_role('ROLE_USER')")
     *@Route("/editprofile", name ="editprofile")
     */
    public function editProfileAction(Request $request) {

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.user_manager');

        //user's new profile values
        $newProfile = new UserForm();
        $newProfile->setEmail($user->getEmail());
        $newProfile->setName($user->getName());

        $form = $this->createForm(new EditProfileForm(),$newProfile);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //get encrypted pass
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);
            $encodedPass = $encoder->encodePassword($newProfile->getOldPass(), $user->getSalt());

            try {
                //check the current pass
                if($encodedPass != $user->getPassword())
                    throw new Exception('Wrong password!');

                //set new pass
                if($newProfile->getNewPass() == $newProfile->getNewPassRetyped()) {
                    if ($newProfile->getNewPass() != '')
                        $user->setPlainPassword($newProfile->getNewPass());
                } else
                    throw new Exception('New passwords have to match! Nothing was saved');

                //set new email
                if($user->getEmail() != $newProfile->getEmail())
                    if($userManager->findUserByEmail($newProfile->getEmail()) == null)
                        $user->setEmail($newProfile->getEmail());
                    else
                        throw new Exception('Sorry, email address already taken. Nothing was saved');

                //set new name
                $user->setName($newProfile->getName());

                $userManager->updateUser($user);
                $request->getSession()->getFlashBag()
                    ->add('success', 'Changes have been saved.');

            } catch (Exception $e) {
                $request->getSession()->getFlashBag()
                    ->add('warning', $e->getMessage());
            }
        }

        return $this->render('User/editProfile.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'form' => $form->createView(),
            'user' => $user,
        ));

    }

}