<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrocAType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        /*$builder   
                ->add('finishedA', 'choice',[
                    'attr' => array('placeholder' => 'Troc effectué ?'),
                    'label'=>'Troc effectué ?' ,
                    'required'=>true,
                    'choices'=> array(1=>'Oui', 0=>'Non'),              
                ]) 
                ->add('addToCaveA', 'choice',[
                    'attr' => array('placeholder' => 'Ajouter ma/mes nouvelle(s) bouteille(s) à ma cave.'),
                    'label'=>'Ajouter ma/mes nouvelle(s) bouteille(s) à ma cave.' ,
                    'required'=>true,
                    'choices'=> array(1=>'Oui', 0=>'Non'),              
                ])
                ->add('noteB', 'choice',[
                    'attr' => array('placeholder' => 'Note*'),
                    'label'=>'Note*' ,
                    'required'=>true,
                    'choices'=> array(0=>0, 1=>1, 2=>2, 3=>3, 4=>4, 5=>5),
                    'preferred_choices' => array(0),
                ])
              ;*/
        $builder   
                ->add('finishedA', 'hidden',[
                    'attr' => array('placeholder' => 'Troc effectué ?'),
                    'label'=>'Troc effectué ?' ,
                    'required'=>true,          
                ]) 
                ->add('addToCaveA', 'hidden',[
                    'attr' => array('placeholder' => 'Ajouter ma/mes nouvelle(s) bouteille(s) à ma cave.'),
                    'label'=>'Ajouter ma/mes nouvelle(s) bouteille(s) à ma cave.' ,
                    'required'=>true,             
                ])
                ->add('noteB', 'hidden',[
                    'attr' => array('placeholder' => 'Note*'),
                    'label'=>'Note*' ,
                    'required'=>true,
                ])
              ;
       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Troc',
            'validation_groups' => array('finA'),
        ));
    }
    
    public function getName()
    {
        return 'TrocAType';
    }
} 
