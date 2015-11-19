<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    
    private $lang;
    
    public function __construct($lang) {
        $this->lang = $lang;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
          ->add('street', 'text',['attr' => array('placeholder' => 'N° voie / Rue'),'label'=>'N° voie / Rue','required'=>false])
          ->add('zipCode', 'text',['attr' => array('placeholder' => 'Code postal'),'label'=>'Code postal', 'required'=>false])
          ->add('city', 'text',['attr' => array('placeholder' => 'Ville'),'label'=>'Ville', 'required'=>false])
          ->add('departement', 'text',['attr' => array('placeholder' => 'Département'),'label'=>'Département', 'required'=>false])
          //->add('region', 'text',['attr' => array('placeholder' => 'Région*'),'label'=>'Région', 'required'=>true])
          ->add('country', 'text',['attr' => array('placeholder' => 'Pays'),'label'=>'Pays', 'required'=>false])
          ;
        
        if($this->lang == 'en'){
            $builder->add('region', 'choice', array(     
                'label'=>'Region',
                'required' => true,
                'empty_value' => 'Region*',
                'choices' => $this->buildRegionsChoices()
                ));
        }else{
            $builder->add('region', 'choice', array(     
                'label'=>'Région',
                'required' => true,
                'empty_value' => 'Région*',
                'choices' => $this->buildRegionsChoices()
                ));
        }
       
    }
    
    
    public function buildRegionsChoices() {
        $arrayRegions = array(
                'Alsace',
                'Aquitaine',
                'Auvergne',
                'Basse-Normandie',
                'Bourgogne',
                'Bretagne',
                'Centre-Val de Loire',
                'Champagne-Ardenne',
                'Corse',
                'Franche-Comté',
                'Guadeloupe',
                'Guyane',
                'Haute-Normandie',
                'Île-de-France',
                'La Réunion',
                'Languedoc-Roussillon',
                'Limousin',
                'Lorraine',
                'Martinique',
                'Mayotte',
                'Midi-Pyrénées',
                'Nord-Pas-de-Calais',
                'Pays de la Loire',
                'Picardie',
                'Poitou-Charentes',
                "Provence-Alpes-Côte d'Azur",
                'Rhône-Alpes');
        return array_combine($arrayRegions,$arrayRegions);
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
