<?php

namespace BackOfficeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class MemberType extends AbstractType
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
                    return $er->createQueryBuilder('el')
                                ->orderBy('el.score', 'ASC');
             }))
            ->add('lastname', 'text', array('attr' => array('placeholder' => 'Nom*'),'label'=>'Nom*'))
            ->add('firstname', 'text', array('attr' => array('placeholder' => 'Prénom*'),'label'=>'Prénom*'))
            ->add('birthdate', 'birthday', array('attr' => array('placeholder' => 'Date de naissance*') ,'label'=>'Date de naissance*','widget' => 'choice'))
            ->add('email', 'email', array('attr' => array('placeholder' => 'Adresse mail*'),'label'=>'Adresse mail*'))
            ->add('telephon', 'text',['attr' => array('placeholder' => 'Téléphone fixe'),'label'=>'Téléphone fixe' ,'required'=>false])
            ->add('mobile', 'text',['attr' => array('placeholder' => 'Téléphone portable'),'label'=>'Téléphone portable' ,'required'=>false])
            ->add('address', new AddressType())
            ->add('avatar', new ImageType(), array(
                    'data_class' => 'AppBundle\Entity\Image',
                    'required' => false,
                ))
            ->add('actif', 'checkbox',[
              'label'=>'Compte actif ?' ,
              'required'=>false,
            ])
            ->add('enabled', 'checkbox',[
              'label'=>'Connexion autorisée ?' ,
              'required'=>false,
            ]);
       
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
