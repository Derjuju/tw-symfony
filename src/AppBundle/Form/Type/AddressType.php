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
          ->add('street', 'text',['required'=>false,'label'=>'N° voie / Rue'])
          ->add('zipCode', 'text',['label'=>'Code postal', 'required'=>false])
          ->add('city', 'text',['label'=>'Ville', 'required'=>false])
          ->add('departement', 'text',['label'=>'Département', 'required'=>false])
          ->add('region', 'text',['label'=>'Région', 'required'=>false])
          ->add('country', 'text',['label'=>'Pays', 'required'=>false])
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
