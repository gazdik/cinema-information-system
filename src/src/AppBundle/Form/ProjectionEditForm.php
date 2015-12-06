<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectionEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'collot_datetime', array(
                'pickerOptions' => array('minView' => 'month',
                'viewSelect' => 'month',
                'autoclose' => true,
                'format' => 'dd/mm/yyyy', ),
                'required' => true,
            ))
            ->add('start', 'collot_datetime', array(
                'pickerOptions' => array('minView' => 'hour',
                'startView' => 'hour',
                'maxView' => 'hour',
                'viewSelect' => 'hour',
                'autoclose' => true,
                'format' => 'hh:ii', ),
                'required' => true,
            ))
            ->add('end', 'collot_datetime', array(
                'pickerOptions' => array('minView' => 'hour',
                'startView' => 'hour',
                'maxView' => 'hour',
                'viewSelect' => 'hour',
                'autoclose' => true,
                'format' => 'hh:ii', ),
                'required' => false,
            ))
            ->add('hall', 'entity', array(
                'class' => 'AppBundle:Hall',
                'empty_data' => null,
                'required' => true,
                'placeholder' => '',
                'choice_label' => 'cinemaAndName',
            ))
            ->add('movie', 'autocomplete', array(
                'class' => 'AppBundle:Movie',
                // 'empty_data' => null,
                'required' => true,
                ))
            ->add('save', 'submit')
            ->add('delete', 'submit')
        ;
    }

    public function getName()
    {
        return 'projectionedit';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Projection',
        ));
    }
}
