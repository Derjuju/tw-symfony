<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class SearchTroqueurType extends AbstractType
{
    private $lang;
    
    public function __construct($lang) {
        $this->lang = $lang;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('keyword', 'text',['required'=>false,'label'=>'Rechercher un troqueur', 'attr' => array('placeholder' => 'Rechercher un troqueur')])
            ->add('region', 'entity', array(
                'label'=>'Région',
                'required'=>false,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'region',
                'empty_value' => 'Région',
                'class' => 'AppBundle:Address',
                'query_builder' => function(EntityRepository $er) {
                        $qb = $er->createQueryBuilder('a');
                        $qb->where($qb->expr()->isNotNull('a.region'))
                                ->groupBy('a.region')
                                ->orderBy('a.region', 'ASC');
                         return $qb;
                }
            ))                
            ;
            
        if($this->lang == 'en'){ 
            $builder
                ->add('expertLevel', 'entity', array(        
                   'label'=>'Niveau',
                   'required'=>false,
                   'expanded' => false,
                   'multiple' => false,
                   'choice_label' => 'nameUk',
                   'empty_value' => 'Niveau',
                   'class' => 'AppBundle:ExpertLevel',
                   'query_builder' => function(EntityRepository $er) {
                           return $er->createQueryBuilder('el')
                                   ->orderBy('el.score', 'ASC');
                   }))
                ; 
            
        }else{
            $builder
                ->add('expertLevel', 'entity', array(        
                   'label'=>'Niveau',
                   'required'=>false,
                   'expanded' => false,
                   'multiple' => false,
                   'choice_label' => 'nameFr',
                   'empty_value' => 'Niveau',
                   'class' => 'AppBundle:ExpertLevel',
                   'query_builder' => function(EntityRepository $er) {
                           return $er->createQueryBuilder('el')
                                   ->orderBy('el.score', 'ASC');
                   }))
                ;
            
        }
       
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
