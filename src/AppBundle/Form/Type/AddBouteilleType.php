<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class AddBouteilleType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
          ->add('typeDeVin', 'entity', array(        
            'label'=>'Type',
            'required' => true,
            'expanded' => false,
            'multiple' => false,
            'choice_label' => 'nameFr',
            'empty_value' => 'Type*',
            'class' => 'AppBundle:TypeDeVin',
            'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                                ->orderBy('e.nameFr', 'ASC');
            }))
          ->add('quantite', 'choice', array(     
            'label'=>'Quantité*',
            'required' => true,
            'empty_value' => 'Quantité*',
            'choices' => $this->buildQuantiteChoices()
            ))
          ->add('note', 'choice',[
              'attr' => array('placeholder' => 'Note*'),
              'label'=>'Note*' ,
              'required'=>true,
              'choices'=> array(0=>0, 1=>1, 2=>2, 3=>3, 4=>4, 5=>5),
              'preferred_choices' => array(0),
            ])
          ->add('typeRegion', 'entity', array(        
            'label'=>'Type',
            'required' => true,
            'expanded' => false,
            'multiple' => false,
            'choice_label' => 'nameFr',
            'empty_value' => 'Région*',
            'class' => 'AppBundle:TypeRegion',
            'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                                ->orderBy('e.nameFr', 'ASC');
            }))
          ->add('millesime', 'choice', array(     
            'label'=>'Millésime',
            'required' => true,
            'empty_value' => 'Millésime*',
            'choices' => $this->buildYearChoices()
            ))
          ->add('typePays', 'entity', array(        
            'label'=>'Type',
            'expanded' => false,
            'multiple' => false,
            'choice_label' => 'nameFr',
            'empty_value' => 'Pays*',
            'class' => 'AppBundle:TypePays',
            'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                                ->orderBy('e.nameFr', 'ASC');
            }))
          ->add('commentaire', 'textarea',['attr' => array('placeholder' => 'Commentaire'),'label'=>'Commentaire' ,'required'=>false])          
          ->add('boiteOrigine', 'checkbox',[
              'label'=>'Dans sa boite d\'origine' ,
              'required'=>false,
            ])
          ->add('niveau', 'choice', array(     
            'label'=>'Niveau',
            'required' => true,
            'empty_data' => '100',
            'choices' => $this->buildNiveauChoices()
            ))
          ->add('typeDomaine', 'entity', array(        
            'label'=>'Type',
            'required'=>false,
            'expanded' => false,
            'multiple' => false,
            'choice_label' => 'nameFr',
            'empty_value' => 'Domaine/Château',
            'class' => 'AppBundle:TypeDomaine',
            'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                                ->orderBy('e.nameFr', 'ASC');
            }))
          ->add('typeAppellation', 'entity', array(        
            'label'=>'Type',
            'required'=>false,
            'expanded' => false,
            'multiple' => false,
            'choice_label' => 'nameFr',
            'empty_value' => 'Appellation',
            'class' => 'AppBundle:TypeAppellation',
            'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                                ->orderBy('e.nameFr', 'ASC');
            }))
          ->add('typeCuvee', 'entity', array(        
            'label'=>'Type',
            'required'=>false,
            'expanded' => false,
            'multiple' => false,
            'choice_label' => 'nameFr',
            'empty_value' => 'Cuvée',
            'class' => 'AppBundle:TypeCuvee',
            'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                                ->orderBy('e.nameFr', 'ASC');
            }))
          ->add('apogee', 'choice', array(     
            'label'=>'Niveau',
            'required' => false,
            'empty_value' => 'Apogée',
            'choices' => $this->buildApogeeChoices()
            ))
          ->add('typeContenance', 'entity', array(        
            'label'=>'Type',
            'expanded' => false,
            'multiple' => false,
            'choice_label' => 'nameFr',
            'empty_value' => 'Contenance*',
            'class' => 'AppBundle:TypeContenance',
            'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                                ->orderBy('e.reference', 'ASC');
            }))
          ->add('typeDeCave', 'entity', array(        
            'label'=>'Type',
            'expanded' => false,
            'multiple' => false,
            'choice_label' => 'nameFr',
            'empty_value' => 'Type de cave*',
            'class' => 'AppBundle:TypeDeCave',
            'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('e');
            }))
            ->add('photo', new ImageType(), array(
                    'data_class' => 'AppBundle\Entity\Image',
                    'required' => false,
                ))
            ->add('photoNiveau', new ImageType(), array(
                    'data_class' => 'AppBundle\Entity\Image',
                    'required' => false,
                ));
       
    }
    
    public function buildYearChoices() {
        //$distance = 5;
        //$yearsBefore = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y") - $distance));
        $yearsBefore = date('Y', mktime(0, 0, 0, 1, 1, 1900));
        $yearsAfter = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        return array_combine(range($yearsAfter, $yearsBefore), range($yearsAfter, $yearsBefore));
    }
    public function buildNiveauChoices() {
        //return array_combine(range(100,0, 10), range(100,0, 10));
        return array_combine(array(80,77,75,72,70,65,60),array('Normal','Bas goulot','Très légèrement bas','Légèrement bas','Haute épaule','Mi-épaule','Bas'));
    }
    public function buildApogeeChoices() {
        return array_combine(range(0,25), range(0,25));
    }
    public function buildQuantiteChoices() {
        return array_combine(range(1,12), range(1,12));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Bouteille',
            'validation_groups' => array('add'),
        ));
    }
    
    public function getName()
    {
        return 'AddBouteilleType';
    }
} 
