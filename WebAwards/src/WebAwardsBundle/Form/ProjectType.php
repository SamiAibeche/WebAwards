<?php

namespace WebAwardsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProjectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('idAuthor')
            ->add('name')
            ->add('description')
            //->add('imgScreen')
            ->add('imgScreen', FileType::class, array('label' => 'Image Laptop', 'data_class' => null))
            //->add('imgMobile')
            ->add('imgMobile', FileType::class, array('label' => 'Image Mobile', 'data_class' => null))
            ->add('url')
            //->add('nbLike')
            //->add('isForward')
            //->add('isVisible')
           // ->add('dateAdd', DateType::class, array('input'  => 'datetime','widget' => 'choice'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WebAwardsBundle\Entity\Project'
        ));
    }
}
