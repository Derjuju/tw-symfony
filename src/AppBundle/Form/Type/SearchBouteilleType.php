<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class SearchBouteilleType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
          ->add('keyword', 'text',['required'=>false,'label'=>'Rechercher une bouteille', 'attr' => array('placeholder' => 'Rechercher une bouteille')])
          ->add('typeDeVin', 'entity', array(        
            'label'=>'Type',
            'required'=>false,
            'expanded' => false,
            'multiple' => false,
            'choice_label' => 'nameFr',
            'empty_value' => 'Type',
            'class' => 'AppBundle:TypeDeVin',
            'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('e');
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
          ->add('millesime', 'choice', array(     
            'label'=>'Millésime',
            'required' => false,
            'empty_value' => 'Millésime',
            'choices' => $this->buildYearChoices()
            ))
          ->add('typePays', 'entity', array(        
            'label'=>'Type',
            'required'=>false,
            'expanded' => false,
            'multiple' => false,
            'choice_label' => 'nameFr',
            'empty_value' => 'Pays',
            'class' => 'AppBundle:TypePays',
            'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('e');
            }))
          ;
       
    }
    
    public function buildYearChoices() {
        //$distance = 5;
        //$yearsBefore = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y") - $distance));
        $yearsBefore = date('Y', mktime(0, 0, 0, 1, 1, 1900));
        $yearsAfter = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        return array_combine(range($yearsAfter, $yearsBefore), range($yearsAfter, $yearsBefore));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Bouteille',
            'validation_groups' => array('research'),
        ));
    }
    
    public function getName()
    {
        return 'SearchBouteille';
    }
} 
