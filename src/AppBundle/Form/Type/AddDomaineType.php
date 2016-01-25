<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddDomaineType extends AbstractType
{
    
    private $lang;
    
    public function __construct($lang) {
        $this->lang = $lang;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        if($this->lang == 'en'){
            $builder          
            ->add('nameUk', 'text',['attr' => array('placeholder' => 'Name'),'label'=>'Name' ,'required'=>true])          
            ;
        }else{
            $builder          
            ->add('nameFr', 'text',['attr' => array('placeholder' => 'Nom'),'label'=>'Nom' ,'required'=>true])          
            ;
        }
       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\TypeDomaine',
            'validation_groups' => array('add'),
        ));
    }
    
    public function getName()
    {
        return 'AddDomaineType';
    }
} 
