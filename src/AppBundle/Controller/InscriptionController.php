<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Member;
use AppBundle\Form\Type\RegisterMemberType;

class InscriptionController extends Controller
{
    /**
     * @Route("/register",name="front_inscription")          
     */
    public function indexAction(Request $request) {
        if ( $this->get('security.context')->isGranted('ROLE_USER') ) {
            throw $this->createNotFoundException('Accès impossible.');
        }
     
        $entity = new Member();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $encoder=$this->get('security.encoder_factory')->getEncoder($entity);
            $salt_generator=$this->get('security.secure_random');
            $entity->generatePassword($encoder,$salt_generator);
            $entity->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
            $entity->setConfirmedEmail(false);
            $entity->setActif(true);
            
            $em->persist($entity);
            $em->flush();
            
            if (!$this->get('mail_to_user')->sendEmailConfirmation($entity->getEmail())) {
                throw $this->createNotFoundException('Unable to send confirmation mail.');
            }

            return $this->redirect($this->generateUrl('front_inscription_confirmation'));
        }

        return $this->render('AppBundle:Inscription:index.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }
    
    /**
     * @Route("/register/confirm",name="front_inscription_confirmation")          
     */
    public function confirmAction(Request $request) {
        return $this->render('AppBundle:Inscription:confirm.html.twig');
    }
    
    /**
     * @Route("/register/confirm/{email}",name="front_valide_email")          
     */
    public function confirmEmailAction($email) {
        $util = $this->getDoctrine()
                ->getManager()
                ->getRepository('AppBundle:Member')
                ->findOneByEmail($email);

        if ($util) {
            if($util->isConfirmedEmail()==1){
                return $this->render('AppBundle:Inscription:confirm_email_already.html.twig');
            }
            $util->setConfirmedEmail(1);
            $util->setEnabled(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($util);
            $em->flush();
            return $this->render('AppBundle:Inscription:confirm_email.html.twig');
        }
        
        return $this->render('AppBundle:Inscription:confirm_email_error.html.twig');
    }
    
    
    /**
    * Creates a form to create a Member entity.
    *
    * @param Member $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Member $entity)
    {
        $form = $this->createForm(new RegisterMemberType(), $entity, array(
            'action' => $this->generateUrl('front_inscription'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Valider'));

        return $form;
    }
}
