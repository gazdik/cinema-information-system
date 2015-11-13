<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HallEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', 'text', array(
                'required' => true,
            ))
            ->add('capacity', 'integer', array(
                'required' => true,
            ))
            ->add('save', 'submit')
            ->add('delete', 'submit')
        ;
    }

    public function getName()
    {
        return 'halledit';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Hall',
        ));
    }
}
