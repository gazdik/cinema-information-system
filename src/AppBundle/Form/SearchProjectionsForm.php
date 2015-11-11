<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchProjectionsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('movie', 'search', array(
            'required' => false,
            ))
          ->add('date', 'collot_datetime', array(
            'pickerOptions' => array('minView' => 'month',
            'viewSelect' => 'month',
            'autoclose' => true,
            'format' => 'dd/mm/yyyy', ),
            'required' => false, ))
          ->add('cinema', 'entity', array(
            'class' => 'AppBundle:Cinema',
            'empty_data' => null,
            'required' => false,
            'placeholder' => 'Cinema',
            'choice_label' => 'name', ))
          ->add('genre', 'entity', array(
            'class' => 'AppBundle:MovieGenre',
            'empty_data' => null,
            'required' => false,
            'placeholder' => 'Genre',
            'choice_label' => 'genre', ))
          ->add('save', 'submit', array('label' => 'Search'))
        ;
    }

    public function getName()
    {
        return 'searchprojections';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Form\SearchProjections',
        ));
    }
}
