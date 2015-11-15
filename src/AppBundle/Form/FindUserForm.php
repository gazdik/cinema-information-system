<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FindUserForm extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add('email', 'email', array(
          'required' => true,
          'label' => "user's email"))
      ->add('save', 'submit', array(
          'label' => 'Search'));
  }

  public function getName() {
    return 'app_findUserForm';
  }
}
