<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfitForm extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add('movie', 'autocomplete', array(
          'class' => 'AppBundle:Movie',
          'required' => false,
        ))
      ->add('cinemas', 'entity', array(
          'class' => 'AppBundle:Cinema',
          'required' => false,
          'empty_data' => null,
          // 'placeholder' => 'Select one or more cinemas',
          'choice_label' => 'name',
          'multiple' => true,
          // 'expanded' => true,
        ))
      ->add('show', 'submit')
      ->add('clear', 'submit', array(
        'validation_groups' => false,
      ));
  }

  public function getName() {
    return 'profit';
  }
}
