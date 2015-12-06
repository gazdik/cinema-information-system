<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'required' => true,
            ))
            ->add('year', 'integer', array(
                'required' => false,
            ))
            ->add('length', 'integer', array(
                'required' => false,
            ))
            ->add('genre', 'entity', array(
                'class' => 'AppBundle:MovieGenre',
                'empty_data' => null,
                'required' => false,
                'choice_label' => 'genre',
            ))
            ->add('save', 'submit')
            ->add('delete', 'submit')
        ;
    }

    public function getName()
    {
        return 'movieedit';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Movie',
        ));
    }
}
