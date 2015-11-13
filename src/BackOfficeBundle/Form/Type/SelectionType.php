<?php

namespace BackOfficeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectionType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder          
          ->add('titreFr', 'textarea',['attr' => array('placeholder' => 'Titre Fr'),'label'=>'Titre Fr' ,'required'=>true]) 
          ->add('titreUk', 'textarea',['attr' => array('placeholder' => 'Titre Uk'),'label'=>'Titre Uk' ,'required'=>true]) 
          ->add('sousTitreFr', 'textarea',['attr' => array('placeholder' => 'Sous Titre Fr'),'label'=>'Sous Titre Fr' ,'required'=>false]) 
          ->add('sousTitreUk', 'textarea',['attr' => array('placeholder' => 'Sous Titre Uk'),'label'=>'Sous Titre Uk' ,'required'=>false]) 
          ->add('lien', 'text',['attr' => array('placeholder' => 'Lien'),'label'=>'Lien' ,'required'=>true])          
          ->add('photo', new ImageType(), array(
                    'data_class' => 'AppBundle\Entity\Image',
                    'required' => false,
                ))
          ;
       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Selection',
        ));
    }
    
    public function getName()
    {
        return 'SelectionType';
    }
} 
