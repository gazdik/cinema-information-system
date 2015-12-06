<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookProjectionForm extends AbstractType {

  private $seats;

  public function __construct($seats) {
    $this->seats = $seats;
  }

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add('seat', 'entity', array(
          'class' => 'AppBundle:Seat',
          'choices' => $this->seats,
          'choice_label' => 'number',
          'required' => true,
          'placeholder' => 'Grab your seat'))
      ->add('priceCategory', 'entity', array(
          'class' => 'AppBundle:priceCategory',
          'required' => true,
          'placeholder' => 'Choose your price',
          'choice_label' => 'category'))
      ->add('save', 'submit', array(
          'label' => 'Book it'));
  }

  public function getName() {
    return 'app_bookProjection';
  }
}
