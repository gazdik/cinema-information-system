<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceCategoryAddForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', 'text', array(
                'required' => true,
            ))
            ->add('categoryPrice', 'number', array(
                'required' => true,
            ))
            ->add('add', 'submit')
        ;
    }

    public function getName()
    {
        return 'categoryadd';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PriceCategory',
        ));
    }
}
