<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('email', 'email', array(
                'label' => "Your email"
            ))
            ->add('name', 'text', array(
                'label' => 'Your name',
            ))
            ->add('newPass', 'password', array(
                'label' => 'New password',
                'required' => false,
            ))
            ->add('newPassRetyped', 'password', array(
                'label' => 'Re-type',
                'required' => false,
            ))
            ->add('oldPass', 'password', array(
                'label' => 'Current password',
                'required' => true,
            ))
            ->add('save', 'submit', array(
                'label' => 'Confirm'));
    }

    public function getName() {
        return 'app_findUserForm';
    }
}