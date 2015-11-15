<?php

namespace BackOfficeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartenaireType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder          
          ->add('titreFr', 'text',['attr' => array('placeholder' => 'Titre Fr*'),'label'=>'Titre Fr*' ,'required'=>true]) 
          ->add('titreUk', 'text',['attr' => array('placeholder' => 'Titre Uk*'),'label'=>'Titre Uk*' ,'required'=>true]) 
          ->add('lien', 'text',['attr' => array('placeholder' => 'Lien*'),'label'=>'Lien*' ,'required'=>true])          
          ->add('position', 'text',['attr' => array('placeholder' => 'Position'),'label'=>'Position' ,'required'=>false])          
          ->add('actif', 'checkbox',[
              'label'=>'Actif' ,
              'required'=>false,
            ])
          ->add('photo', new ImageType(), array(
                    'data_class' => 'AppBundle\Entity\Image',
                    'required' => false,
                ))
          ;
       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Partenaire',
        ));
    }
    
    public function getName()
    {
        return 'PartenaireType';
    }
} 
