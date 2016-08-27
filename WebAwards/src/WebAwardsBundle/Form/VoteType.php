<?php

namespace WebAwardsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idProject')
            ->add('idUser')
            ->add('nbDesign')
            ->add('nbFluidity')
            ->add('nbConcept')
            ->add('nbContent')
            ->add('nbTotal');
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WebAwardsBundle\Entity\Vote'
        ));
    }
}
