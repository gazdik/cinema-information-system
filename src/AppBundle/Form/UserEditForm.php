<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array(
                'required' => true,
            ))
            ->add('email', 'email', array(
                'required' => true,
            ))
            ->add('roles', 'choice', array(
                'required' => true,
                'choices' => array('ROLE_USER' => 0, 'ROLE_CASHIER' => 1, 'ROLE_ADMIN' => 2, 'ROLE_MANAGER' =>3),
                'multiple' => true,
            ))
            ->add('enabled', 'checkbox', array(
                'required' => false,
            ))
            ->add('save', 'submit')
            ->add('delete', 'submit')
        ;
    }

    public function getName()
    {
        return 'useredit';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
        ));
    }
}
