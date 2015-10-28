<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
          ->add('street', 'text',['attr' => array('placeholder' => 'N° voie / Rue'),'label'=>'N° voie / Rue','required'=>false])
          ->add('zipCode', 'text',['attr' => array('placeholder' => 'Code postal'),'label'=>'Code postal', 'required'=>false])
          ->add('city', 'text',['attr' => array('placeholder' => 'Ville'),'label'=>'Ville', 'required'=>false])
          ->add('departement', 'text',['attr' => array('placeholder' => 'Département'),'label'=>'Département', 'required'=>false])
          ->add('region', 'text',['attr' => array('placeholder' => 'Région*'),'label'=>'Région', 'required'=>true])
          ->add('country', 'text',['attr' => array('placeholder' => 'Pays'),'label'=>'Pays', 'required'=>false])
          ;
       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Address',
            'validation_groups' => array('registration'),
        ));
    }
    
    public function getName()
    {
        return 'Adresse';
    }
} 
