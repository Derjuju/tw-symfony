<?php

namespace BackOfficeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class NouvelleAddressTWType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('name', 'text', array('attr' => array('placeholder' => 'Nom*'),'label'=>'Nom*'))
            ->add('street', 'text',['attr' => array('placeholder' => 'N° voie / Rue'),'label'=>'N° voie / Rue','required'=>false])
            ->add('zipCode', 'text',['attr' => array('placeholder' => 'Code postal*'),'label'=>'Code postal*', 'required'=>true])
            ->add('city', 'text',['attr' => array('placeholder' => 'Ville*'),'label'=>'Ville*', 'required'=>true])
            ->add('departement', 'text',['attr' => array('placeholder' => 'Département'),'label'=>'Département', 'required'=>false])
            ->add('region', 'text',['attr' => array('placeholder' => 'Région*'),'label'=>'Région*', 'required'=>true])
            ->add('country', 'text',['attr' => array('placeholder' => 'Pays'),'label'=>'Pays', 'required'=>false, 'data' => 'France'])
            ;
        
        $builder->add('region', 'choice', array(     
                'label'=>'Région*',
                'required' => true,
                'empty_value' => 'Région*',
                'choices' => $this->buildRegionsChoices()
                ));
       
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
            'data_class' => 'AppBundle\Entity\AddressTW',
        ));
    }
    
    public function getName()
    {
        return 'AddAddressTW';
    }
} 
