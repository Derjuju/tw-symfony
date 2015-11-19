<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class SearchBouteilleType extends AbstractType
{
    private $lang;
    
    public function __construct($lang) {
        $this->lang = $lang;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
          ->add('keyword', 'text',['required'=>false,'label'=>'Rechercher une bouteille', 'attr' => array('placeholder' => 'Rechercher une bouteille')])
          ->add('millesime', 'choice', array(     
            'label'=>'Millésime',
            'required' => false,
            'empty_value' => 'Millésime',
            'choices' => $this->buildYearChoices()
            ))          
          ;
        if($this->lang == 'en'){            
            $builder
                ->add('typeDeVin', 'entity', array(        
                  'label'=>'Type',
                  'required'=>false,
                  'expanded' => false,
                  'multiple' => false,
                  'choice_label' => 'nameUk',
                  'empty_value' => 'Type',
                  'class' => 'AppBundle:TypeDeVin',
                  'query_builder' => function(EntityRepository $er) {
                          return $er->createQueryBuilder('e')
                                      ->orderBy('e.nameUk', 'ASC');
                  }))
                ->add('typeRegion', 'entity', array(        
                  'label'=>'Type',
                  'required'=>false,
                  'expanded' => false,
                  'multiple' => false,
                  'choice_label' => 'nameUk',
                  'empty_value' => 'Région',
                  'class' => 'AppBundle:TypeRegion',
                  'query_builder' => function(EntityRepository $er) {
                          return $er->createQueryBuilder('e')
                                      ->orderBy('e.nameUk', 'ASC');
                  }))
                  ->add('typePays', 'entity', array(        
                  'label'=>'Type',
                  'required'=>false,
                  'expanded' => false,
                  'multiple' => false,
                  'choice_label' => 'nameUk',
                  'empty_value' => 'Pays',
                  'class' => 'AppBundle:TypePays',
                  'query_builder' => function(EntityRepository $er) {
                          return $er->createQueryBuilder('e')
                                      ->orderBy('e.nameUk', 'ASC');
                  }))
                ;
        }else{
            $builder
                ->add('typeDeVin', 'entity', array(        
                  'label'=>'Type',
                  'required'=>false,
                  'expanded' => false,
                  'multiple' => false,
                  'choice_label' => 'nameFr',
                  'empty_value' => 'Type',
                  'class' => 'AppBundle:TypeDeVin',
                  'query_builder' => function(EntityRepository $er) {
                          return $er->createQueryBuilder('e')
                                      ->orderBy('e.nameFr', 'ASC');
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
                          return $er->createQueryBuilder('e')
                                      ->orderBy('e.nameFr', 'ASC');
                  }))
                  ->add('typePays', 'entity', array(        
                  'label'=>'Type',
                  'required'=>false,
                  'expanded' => false,
                  'multiple' => false,
                  'choice_label' => 'nameFr',
                  'empty_value' => 'Pays',
                  'class' => 'AppBundle:TypePays',
                  'query_builder' => function(EntityRepository $er) {
                          return $er->createQueryBuilder('e')
                                      ->orderBy('e.nameFr', 'ASC');
                  }))
                ;
        }
        
          
       
    }
    
    public function buildYearChoices() {
        //$distance = 5;
        //$yearsBefore = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y") - $distance));
        $yearsBefore = date('Y', mktime(0, 0, 0, 1, 1, 1900));
        $yearsAfter = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        $arrayDates = array_combine(range($yearsAfter, $yearsBefore), range($yearsAfter, $yearsBefore));
        $arrayNoDates = array_combine(array(0),array('Non millésimé'));
        
        //$arrayMerged = array_merge($arrayNoDates,$arrayDates);
        $arrayMerged = $arrayNoDates + $arrayDates;
        
        return $arrayMerged;
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
