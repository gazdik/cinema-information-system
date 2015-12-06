<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketSellForm extends AbstractType {

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
        ))
      ->add('priceCategory', 'entity', array(
          'class' => 'AppBundle:priceCategory',
          'required' => true,
          'choice_label' => 'category'
        ))
      ->add('sell', 'submit');
  }

  public function getName() {
    return 'ticketsell';
  }
}
