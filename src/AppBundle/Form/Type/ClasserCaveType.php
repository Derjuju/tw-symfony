<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class ClasserCaveType extends AbstractType
{
    
    private $lang;
    
    public function __construct($lang) {
        $this->lang = $lang;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($this->lang == 'en'){
            $builder
                ->add('typeDeVin', 'entity', array(        
                  'label'=>'Type',
                  'expanded' => false,
                  'multiple' => false,
                  'choice_label' => 'nameUk',
                  'placeholder' => 'Sorted by...',
                  'empty_value' => 'Sorted by...',
                  'empty_data'  => '0',
                  'class' => 'AppBundle:TypeDeVin',
                  'query_builder' => function(EntityRepository $er) {
                          return $er->createQueryBuilder('e')
                                      ->orderBy('e.nameUk', 'ASC');
                  }))
                  ;
        }else{
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
                          return $er->createQueryBuilder('e')
                                      ->orderBy('e.nameFr', 'ASC');
                  }))
                  ;
        }
       
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
