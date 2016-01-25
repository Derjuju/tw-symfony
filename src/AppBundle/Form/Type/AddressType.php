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
        
        if($this->lang == 'en'){
            $builder
                ->add('street', 'text',['attr' => array('placeholder' => 'Street name'),'label'=>'Street name','required'=>true])
                ->add('zipCode', 'text',['attr' => array('placeholder' => 'Zipcode'),'label'=>'Zipcode', 'required'=>true])
                ->add('city', 'text',['attr' => array('placeholder' => 'City'),'label'=>'City', 'required'=>true])
                ->add('departement', 'text',['attr' => array('placeholder' => 'Departement'),'label'=>'Departement', 'required'=>false])
                ->add('country', 'text',['attr' => array('placeholder' => 'Country'),'label'=>'Country', 'required'=>false])
                ;



              $builder->add('region', 'choice', array(     
                  'label'=>'Region',
                  'required' => false,
                  'empty_value' => 'Region',
                  'choices' => $this->buildRegionsChoices()
                  ));
        }else{
            $builder
                ->add('street', 'text',['attr' => array('placeholder' => 'N° voie / Rue'),'label'=>'N° voie / Rue','required'=>true])
                ->add('zipCode', 'text',['attr' => array('placeholder' => 'Code postal'),'label'=>'Code postal', 'required'=>true])
                ->add('city', 'text',['attr' => array('placeholder' => 'Ville'),'label'=>'Ville', 'required'=>true])
                ->add('departement', 'text',['attr' => array('placeholder' => 'Département'),'label'=>'Département', 'required'=>false])
                ->add('country', 'text',['attr' => array('placeholder' => 'Pays'),'label'=>'Pays', 'required'=>false])
                ;



              $builder->add('region', 'choice', array(     
                  'label'=>'Région',
                  'required' => false,
                  'empty_value' => 'Région',
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
