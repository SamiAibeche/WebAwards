<?php

namespace WebAwardsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('birthdayAt', DateType::class, array('input'  => 'datetime','widget' => 'choice'))
            ->add('email')
            ->add('img')
            ->add('role')
            ->add('isPublisher')
            ->add('isSubscribe')
            ->add('isAdmin')
            ->add('dateAff', DateType::class, array('input'  => 'datetime','widget' => 'choice'))
            ->add('sexe')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WebAwardsBundle\Entity\User'
        ));
    }
}
