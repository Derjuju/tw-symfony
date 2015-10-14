<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class ClasserCaveType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
          ->add('typeDeVin', 'entity', array(        
            'label'=>'Type',
            'expanded' => false,
            'multiple' => false,
            'choice_label' => 'nameFr',
            'placeholder' => 'Classer par...',
            'empty_value' => 'Classer par...',
            'empty_data'  => '0',
            'class' => 'AppBundle:TypeDeVin',
            'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('e');
            }))
            ;
       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Bouteille',
            'validation_groups' => array('sort'),
        ));
    }
    
    public function getName()
    {
        return 'ClasserCave';
    }
} 
