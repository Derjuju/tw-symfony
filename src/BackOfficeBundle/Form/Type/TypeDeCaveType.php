<?php

namespace BackOfficeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeDeCaveType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder          
          ->add('nameFr', 'text',['attr' => array('placeholder' => 'Nom Fr'),'label'=>'Nom Fr' ,'required'=>true]) 
          ->add('nameUk', 'text',['attr' => array('placeholder' => 'Nom Uk'),'label'=>'Nom Uk' ,'required'=>true]) 
          ->add('reference', 'text',['attr' => array('placeholder' => 'reference'),'label'=>'Référence' ,'required'=>true])                    
          ;
       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\TypeDeCave',
        ));
    }
    
    public function getName()
    {
        return 'TypeDeCaveType';
    }
} 
