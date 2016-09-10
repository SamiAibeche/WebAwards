<?php

namespace WebAwardsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


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
            ->add('birthdayAt', DateType::class, array('input'  => 'datetime','widget' => 'choice','years' => range(date('Y') - 66, date('Y') - 18)
            ))
            ->add('email')
            ->add('username')
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('img', FileType::class, array('label' => 'Image Profil', 'data_class' => null))
            ->add('role',ChoiceType::class , array(
                'choices'  => array(
                    'Une agence' => 'agency',
                    'Indépendent' => 'freelance',
                    'Juste moi !' => 'me',
                ),
                'multiple' => false,
            ))

            //->add('isPublisher')
            //->add('isSubscribe')
            //->add('isAdmin')
            //->add('dateAff', DateType::class, array('input'  => 'datetime','widget' => 'choice'))
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
