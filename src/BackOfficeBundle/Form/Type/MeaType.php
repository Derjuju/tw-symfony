<?php

namespace BackOfficeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeaType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder          
          ->add('nameFr', 'text',['attr' => array('placeholder' => 'Nom Fr*'),'label'=>'Nom Fr*' ,'required'=>true]) 
          ->add('nameUk', 'text',['attr' => array('placeholder' => 'Nom Uk*'),'label'=>'Nom Uk*' ,'required'=>true]) 
          ->add('referenceVin', 'text',['attr' => array('placeholder' => 'Reference type vin*'),'label'=>'Reference type vin*' ,'required'=>true])                          
          ->add('position', 'choice',[
              'attr' => array('placeholder' => 'Position*'),
              'label'=>'Position*' ,
              'required'=>true,
              'choices'=> array(1=>1, 2=>2, 3=>3, 4=>4),
              'preferred_choices' => array(1),
            ])
         ->add('actif', 'checkbox',[
              'label'=>'Actif' ,
              'required'=>false,
            ])
         ->add('photo', new ImageType(), array(
                    'data_class' => 'AppBundle\Entity\Image',
                    'required' => false,
                ))
          ;
       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Mea',
        ));
    }
    
    public function getName()
    {
        return 'MeaType';
    }
} 
