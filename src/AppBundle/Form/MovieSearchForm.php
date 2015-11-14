<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieSearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'required' => false,
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
                'placeholder' => 'Genre',
                'choice_label' => 'genre',
            ))
            ->add('search', 'submit')
        ;
    }

    public function getName()
    {
        return 'moviesearch';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Movie',
        ));
    }
}
