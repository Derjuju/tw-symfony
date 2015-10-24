<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChgPwdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
          ->add('tmppwd','password',['label'=>'Ancien mot de passe','mapped'=>false])
          ->add('password','repeated', ['first_name'=>'pass', 'second_name'=>'confirm','first_options'=>['label'=>'Nouveau mot de passe'], 'second_options'=>['label'=>'confirmez le mot de passe'], 'invalid_message'=>'Les mots de passe sont différents', 'label'=>'password', 'type'=>'password'])
          ;
       
    }


    
    public function getName()
    {
        return 'ChgPwdType';
    }
} 
