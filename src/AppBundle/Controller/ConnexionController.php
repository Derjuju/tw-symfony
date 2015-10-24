<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

use AppBundle\Entity\Member;
use AppBundle\Form\Type\UsernameMemberType;

class ConnexionController extends Controller
{
    /**
     * @Route("/pbconnexion",name="front_pbconnexion")          
     */
    public function indexAction(Request $request) {
        
        $user=new Member();
        
        $form=$this->createForm(new UsernameMemberType(),$user);                          
        $form->add('Envoyer','submit');
        
        $emailValid=-1;
        if ($form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $emailValid=0;

            $login = $form["login"]->getData();
            $email = $form["email"]->getData();
            if($login == '' && $email == ''){
                $form->get('login')->addError(new FormError("Vous devez indiquer votre pseudo ou votre adresse email."));
            }else{
                if($login != ''){
                    $util =  $em->getRepository('AppBundle:Member')->findOneBy(array('login'=>$login));
                    if (!$util)
                    {
                        $form->get('login')->addError(new FormError("Ce pseudo n'est pas connue."));
                    }else{
                        $emailValid=1;
                    }
                }else{
                    $util =  $em->getRepository('AppBundle:Member')->findOneBy(array('email'=>$email));
                    if (!$util)
                    {
                        $form->get('email')->addError(new FormError("Cette adresse mail n'est pas connue."));
                    }else{
                        $emailValid=1;
                    }
                }
                if($emailValid==1){
                    
                      // Generation d'un mot de passe temporaire
                      $pass = $em->getRepository('AppBundle:Member')->generateTempPassword();

                      $encoder=$this->get('security.encoder_factory')->getEncoder($util);
                      $encodedPassword = $encoder->encodePassword($pass, $util->getSalt());
                      $util->setPassword($encodedPassword);
                      
                      $em->persist($util);
                      $em->flush();

                      if(!$this->get('mail_to_user')->sendEmailNouveauxIdentifiants($util->getEmail(), $util->getLogin(), $pass, $util->getFirstname())){
                          throw $this->createNotFoundException('Unable to send mail.');
                      }
                      
                }
            }
        }
        
        return $this->render("AppBundle:Connexion:pbconnexion.html.twig", ['email_form' => $form->createView(),'emailValid'=>$emailValid]);
    }
    
}
