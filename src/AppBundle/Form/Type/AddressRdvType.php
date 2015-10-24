<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressRdvType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
          ->add('name', 'text',['attr' => array('placeholder' => 'Donner un nom'),'label'=>'Donner un nom','required'=>true])
          ->add('street', 'text',['attr' => array('placeholder' => 'N° voie / Rue'),'label'=>'N° voie / Rue','required'=>true])
          ->add('zipCode', 'text',['attr' => array('placeholder' => 'Code postal'),'label'=>'Code postal', 'required'=>true])
          ->add('city', 'text',['attr' => array('placeholder' => 'Ville'),'label'=>'Ville', 'required'=>true])
          ->add('departement', 'text',['attr' => array('placeholder' => 'Département'),'label'=>'Département', 'required'=>false])
          ->add('region', 'text',['attr' => array('placeholder' => 'Région'),'label'=>'Région', 'required'=>false])
          ->add('country', 'text',['attr' => array('placeholder' => 'Pays'),'label'=>'Pays', 'required'=>false])
          ;
       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\AddressRdv',
            'validation_groups' => array('registration'),
        ));
    }
    
    public function getName()
    {
        return 'AddressRdv';
    }
} 
