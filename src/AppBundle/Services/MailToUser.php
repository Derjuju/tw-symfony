<?php

namespace AppBundle\Services;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use Assetic\Exception\Exception;

use AppBundle\Entity\Event;

class MailToUser {

    protected $mailer;
    protected $router;
    protected $templating;
    protected $app_front_url;
    protected $kernel;
    private $from = "derjuju@gmail.com";
    private $reply = "derjuju@gmail.com";
    private $name = "TrocWine";

    public function __construct($mailer, EngineInterface $templating, RouterInterface $router, $app_front_url, $kernel) {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;
        $this->app_front_url = $app_front_url;
        $this->kernel = $kernel;
    }
    
    public function sendEmailConfirmation($to){
        $view = null;
        $view = $this->templating->render('AppBundle:Mailing:confirm_email.html.twig', array());
        if (!$view)
            return false;
        
        // sujet
        $subject = "[TrocWine] Confirmation du profil";
        
        // variables dynamiques
        $lien_confirmation_email = $this->app_front_url.$this->router->generate('front_valide_email', array('email' => $to));
        $lien_confirmation_email = str_replace('//valid', '/valid', $lien_confirmation_email);
        $view = str_replace('#LIEN_DE_CONFIRMATION_EMAIL#',$lien_confirmation_email, $view);
                        
        return $this->sendMail($subject, $view, $to);
    }
    
    public function sendEmailNouveauxIdentifiants($to, $login, $tmpPass){
        $view = null;
        $view = $this->templating->render('AppBundle:Mailing:id_perdus.html.twig', array());
        if (!$view)
            return false;
        
        // sujet
        $subject = "[TrocWine] Identifiants perdus";
        
        // variables dynamiques
        $view = str_replace('#mailtoCercle#',"mailto:".$this->reply, $view);
        $view = str_replace('#Identifiant#',$login, $view);
        $view = str_replace('#MotDePasseTemporaire#',$tmpPass, $view);
        
        
        return $this->sendMail($subject, $view, $to);
    }
    
    public function sendEmailConfirmNouveauMDP($to, $name, $civility){
        $view = null;
        $view = $this->templating->render('AppBundle:Mailing:confirm_new_mdp.html.twig', array());
        if (!$view)
            return false;
        
        // sujet
        $subject = "[TrocWine] Confirmation modification mot de passe";
        
        // variables dynamiques
        $view = str_replace('#mailtoCercle#',"mailto:".$this->reply, $view);
        $view = str_replace('#Nom#',$name, $view);
        $view = str_replace('#Civilite#',$civility, $view);
        
        
        return $this->sendMail($subject, $view, $to);
    }
    
    
    
    public function sendEmailNouveauTroc($to){
        $view = null;
        $view = $this->templating->render('AppBundle:Mailing:nouveau_troc.html.twig', array());
        if (!$view)
            return false;
        
        // sujet
        $subject = "[TrocWine] Nouveau troc proposé";
        
        // variables dynamiques
        $lien_acces_espace = $this->app_front_url.$this->router->generate('front_accueil_connexion');
        $lien_acces_espace = str_replace('//homepage', '/homepage', $lien_acces_espace);
        $view = str_replace('#LIEN_ACCES_ESPACE#',$lien_acces_espace, $view);
                        
        return $this->sendMail($subject, $view, $to);
    }
    
    public function sendEmailNouveauTrocOwner($to){
        $view = null;
        $view = $this->templating->render('AppBundle:Mailing:nouveau_troc_owner.html.twig', array());
        if (!$view)
            return false;
        
        // sujet
        $subject = "[TrocWine] Nouveau troc proposé";
        
        // variables dynamiques
        $lien_acces_espace = $this->app_front_url.$this->router->generate('front_accueil_connexion');
        $lien_acces_espace = str_replace('//homepage', '/homepage', $lien_acces_espace);
        $view = str_replace('#LIEN_ACCES_ESPACE#',$lien_acces_espace, $view);
                        
        return $this->sendMail($subject, $view, $to);
    }
    
    
    
    public function sendEmailModifierTroc($to){
        $view = null;
        $view = $this->templating->render('AppBundle:Mailing:modifier_troc.html.twig', array());
        if (!$view)
            return false;
        
        // sujet
        $subject = "[TrocWine] Modification du troc proposé";
        
        // variables dynamiques
        $lien_acces_espace = $this->app_front_url.$this->router->generate('front_accueil_connexion');
        $lien_acces_espace = str_replace('//homepage', '/homepage', $lien_acces_espace);
        $view = str_replace('#LIEN_ACCES_ESPACE#',$lien_acces_espace, $view);
                        
        return $this->sendMail($subject, $view, $to);
    }
    
    public function sendEmailModifierTrocOwner($to){
        $view = null;
        $view = $this->templating->render('AppBundle:Mailing:modifier_troc_owner.html.twig', array());
        if (!$view)
            return false;
        
        // sujet
        $subject = "[TrocWine] Modification du troc proposé";
        
        // variables dynamiques
        $lien_acces_espace = $this->app_front_url.$this->router->generate('front_accueil_connexion');
        $lien_acces_espace = str_replace('//homepage', '/homepage', $lien_acces_espace);
        $view = str_replace('#LIEN_ACCES_ESPACE#',$lien_acces_espace, $view);
                        
        return $this->sendMail($subject, $view, $to);
    }
    

    private function sendMail($subject, $view, $to){
        
        $view = $this->createOnlineVersion($view);
        
        $mail = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($this->from, $this->name)
                ->setBody($view)
                ->setReplyTo($this->reply, $this->name)
                ->setContentType('text/html');
        
        try {
            $mail->setTo($to);
            $this->mailer->send($mail);
        } catch (Exception $exc) {
            return false;
        }                 

        return true;
    }
    
    private function createOnlineVersion($view){
        
        $filename = md5(uniqid(null, true).date("YmdHis"));
        $filenameWithExt = $filename.".dat";
        
        // variables dynamiques
        $lien_version_online = $this->app_front_url.$this->router->generate('front_online_version', array('type' => 'mail', 'hash' => $filename));
        $newView = str_replace('#LIEN_ONLINE#',$lien_version_online, $view);        
        
        // traitement du fichier justificatif
        $dir=$this->kernel->getRootDir()."/../web/".$this->kernel->getContainer()->getParameter('online_mail_dir');

        if (!is_dir($dir)) { mkdir($dir); }
        
        file_put_contents($dir.$filenameWithExt, $newView);
        
        return $newView;
    }
}
