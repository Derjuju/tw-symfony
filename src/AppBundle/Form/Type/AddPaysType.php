<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddPaysType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder          
          ->add('nameFr', 'text',['attr' => array('placeholder' => 'Nom'),'label'=>'Nom' ,'required'=>true])          
          ;
       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\TypePays',
            'validation_groups' => array('add'),
        ));
    }
    
    public function getName()
    {
        return 'AddPaysType';
    }
} 
