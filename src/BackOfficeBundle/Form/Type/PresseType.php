<?php

namespace BackOfficeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PresseType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder          
          ->add('titreFr', 'text',['attr' => array('placeholder' => 'Titre Fr*'),'label'=>'Titre Fr*' ,'required'=>true]) 
          ->add('titreUk', 'text',['attr' => array('placeholder' => 'Titre Uk*'),'label'=>'Titre Uk*' ,'required'=>true]) 
          ->add('lien', 'text',['attr' => array('placeholder' => 'Lien*'),'label'=>'Lien*' ,'required'=>true])          
          ->add('createdAt', 'date', array('attr' => array('placeholder' => 'Date de parution*') ,'label'=>'Date de parution*','widget' => 'choice','format' => 'dd/MM/yyyy', 'required'=>true))          
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
            'data_class' => 'AppBundle\Entity\Presse',
        ));
    }
    
    public function getName()
    {
        return 'PresseType';
    }
} 
