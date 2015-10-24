<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UsernameMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('login', 'text', array('attr' => array('placeholder' => 'Pseudo*'),'label'=>'Pseudo*', 'required' => false))
            ->add('email', 'email', array('attr' => array('placeholder' => 'Adresse mail*'),'label'=>'Adresse mail*', 'required' => false))
        ;
       
    }


    
    public function getName()
    {
        return 'UsernameMember';
    }
} 
