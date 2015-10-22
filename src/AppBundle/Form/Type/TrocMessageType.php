<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrocMessageType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder          
          ->add('message', 'textarea',['attr' => array('placeholder' => 'Message'),'label'=>'Message' ,'required'=>true])          
          ;
       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\TrocMessage',
            'validation_groups' => array('add'),
        ));
    }
    
    public function getName()
    {
        return 'TrocMessageType';
    }
} 
