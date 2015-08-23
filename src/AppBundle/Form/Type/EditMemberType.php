<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class EditMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('expertLevel', 'entity', array(        
            'label'=>'Niveau*',
            'expanded' => false,
            'multiple' => false,
            'choice_label' => 'nameFr',
            'class' => 'AppBundle:ExpertLevel',
            'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('el');
             }))
            ->add('lastname', 'text', array('attr' => array('placeholder' => 'Nom*'),'label'=>'Nom*'))
            ->add('firstname', 'text', array('attr' => array('placeholder' => 'Prénom*'),'label'=>'Prénom*'))
            ->add('birthdate', 'date', array('attr' => array('placeholder' => 'Date de naissance*') ,'label'=>'Date de naissance*','widget' => 'single_text','format' => 'dd/MM/yyyy'))
            ->add('email', 'email', array('attr' => array('placeholder' => 'Adresse mail*'),'label'=>'Adresse mail*'))
            ->add('telephon', 'text',['attr' => array('placeholder' => 'Téléphone fixe'),'label'=>'Téléphone fixe' ,'required'=>false])
            ->add('mobile', 'text',['attr' => array('placeholder' => 'Téléphone portable'),'label'=>'Téléphone portable' ,'required'=>false])
            ->add('address', new AddressType());
       
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Member',
            'validation_groups' => array('edit'),
        ));
    }
    
    public function getName()
    {
        return 'EditMember';
    }
} 
