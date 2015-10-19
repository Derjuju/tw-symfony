<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class SearchTroqueurType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('keyword', 'text',['required'=>false,'label'=>'Rechercher un troqueur', 'attr' => array('placeholder' => 'Rechercher un troqueur')])
            ->add('expertLevel', 'entity', array(        
                'label'=>'Niveau',
                'required'=>false,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'nameFr',
                'empty_value' => 'Niveau',
                'class' => 'AppBundle:ExpertLevel',
                'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('el');
                }))
            ->add('typeRegion', 'entity', array(        
                'label'=>'Type',
                'required'=>false,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'nameFr',
                'empty_value' => 'Région',
                'class' => 'AppBundle:TypeRegion',
                'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('e');
                }))
            ;
       
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Member',
            'validation_groups' => array('research'),
        ));
    }
    
    public function getName()
    {
        return 'SearchTroqueur';
    }
} 
