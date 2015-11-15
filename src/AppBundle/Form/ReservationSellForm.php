<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationSellForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('priceCategory', 'entity', array(
                'class' => 'AppBundle:PriceCategory',
                'required' => true,
                'choice_label' => 'category',
            ))
            ->add('sell', 'submit')
        ;
    }

    public function getName()
    {
        return 'reservationsell';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Ticket',
        ));
    }
}
