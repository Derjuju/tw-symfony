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
    private $from = "info@trocwine.com";
    private $reply = "info@trocwine.com";
    private $name = "TrocWine";

    public function __construct($mailer, EngineInterface $templating, RouterInterface $router, $app_front_url, $kernel) {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;
        $this->app_front_url = $app_front_url;
        $this->kernel = $kernel;
    }
    
    public function sendEmailConfirmation($to, $prenom){
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
        $view = str_replace('#PRENOM#',$prenom, $view);
                        
        return $this->sendMail($subject, $view, $to);
    }
    
    public function sendEmailNouveauxIdentifiants($to, $login, $tmpPass, $prenom){
        $view = null;
        $view = $this->templating->render('AppBundle:Mailing:id_perdus.html.twig', array());
        if (!$view)
            return false;
        
        // sujet
        $subject = "[TrocWine] Identifiants perdus";
        
        // variables dynamiques
        $lien_acces_espace = $this->app_front_url.$this->router->generate('front_accueil_connexion');
        $lien_acces_espace = str_replace('//homepage', '/homepage', $lien_acces_espace);
        $view = str_replace('#LIEN_ACCES_DIRECT#',$lien_acces_espace, $view);
        $view = str_replace('#PRENOM#',$prenom, $view);
        $view = str_replace('#Identifiant#',$login, $view);
        $view = str_replace('#MotDePasseTemporaire#',$tmpPass, $view);
        
        $lien_confirmation_email = $this->app_front_url.$this->router->generate('front_valide_email', array('email' => $to));
        $lien_confirmation_email = str_replace('//valid', '/valid', $lien_confirmation_email);
        $view = str_replace('#LIEN_DE_CONFIRMATION_EMAIL#',$lien_confirmation_email, $view);
        
        
        return $this->sendMail($subject, $view, $to);
    }
    
    public function sendEmailConfirmNouveauMDP($to, $login, $tmpPass, $prenom){
        $view = null;
        $view = $this->templating->render('AppBundle:Mailing:confirm_new_mdp.html.twig', array());
        if (!$view)
            return false;
        
        // sujet
        $subject = "[TrocWine] Confirmation modification mot de passe";
        
        // variables dynamiques
        $lien_acces_espace = $this->app_front_url.$this->router->generate('front_accueil_connexion');
        $lien_acces_espace = str_replace('//homepage', '/homepage', $lien_acces_espace);
        $view = str_replace('#LIEN_ACCES_DIRECT#',$lien_acces_espace, $view);
        $view = str_replace('#PRENOM#',$prenom, $view);
        $view = str_replace('#Identifiant#',$login, $view);
        $view = str_replace('#MotDePasseTemporaire#',$tmpPass, $view);
        
        
        return $this->sendMail($subject, $view, $to);
    }
    
    
    
    public function sendEmailNouveauTroc($to, $refTroc, $prenom1, $prenom2){
        $view = null;
        $view = $this->templating->render('AppBundle:Mailing:nouveau_troc.html.twig', array());
        if (!$view)
            return false;
        
        // sujet
        $subject = "[TrocWine] #".$refTroc." - Nouveau troc proposé";
        
        // variables dynamiques
        $lien_acces_espace = $this->app_front_url.$this->router->generate('front_accueil_connexion');
        $lien_acces_espace = str_replace('//homepage', '/homepage', $lien_acces_espace);
        $view = str_replace('#LIEN_ACCES_DIRECT#',$lien_acces_espace, $view);
        $view = str_replace('#PRENOM1#',$prenom1, $view);
        $view = str_replace('#PRENOM2#',$prenom2, $view);
                        
        return $this->sendMail($subject, $view, $to);
    }
    
    public function sendEmailNouveauTrocOwner($to, $refTroc, $prenom1, $prenom2){
        $view = null;
        $view = $this->templating->render('AppBundle:Mailing:nouveau_troc_owner.html.twig', array());
        if (!$view)
            return false;
        
        // sujet
        $subject = "[TrocWine] #".$refTroc." - Nouveau troc proposé";
        
        // variables dynamiques
        $lien_acces_espace = $this->app_front_url.$this->router->generate('front_accueil_connexion');
        $lien_acces_espace = str_replace('//homepage', '/homepage', $lien_acces_espace);
        $view = str_replace('#LIEN_ACCES_DIRECT#',$lien_acces_espace, $view);
        $view = str_replace('#PRENOM1#',$prenom1, $view);
        $view = str_replace('#PRENOM2#',$prenom2, $view);
                        
        return $this->sendMail($subject, $view, $to);
    }
    
    
    
    public function sendEmailModifierTroc($to, $refTroc, $prenom1, $prenom2){
        $view = null;
        $view = $this->templating->render('AppBundle:Mailing:modifier_troc.html.twig', array());
        if (!$view)
            return false;
        
        // sujet
        $subject = "[TrocWine] #".$refTroc." - Modification du troc proposé";
        
        // variables dynamiques
        $lien_acces_espace = $this->app_front_url.$this->router->generate('front_accueil_connexion');
        $lien_acces_espace = str_replace('//homepage', '/homepage', $lien_acces_espace);
        $view = str_replace('#LIEN_ACCES_DIRECT#',$lien_acces_espace, $view);
        $view = str_replace('#PRENOM1#',$prenom1, $view);
        $view = str_replace('#PRENOM2#',$prenom2, $view);
                        
        return $this->sendMail($subject, $view, $to);
    }
    
    public function sendEmailModifierTrocOwner($to, $refTroc, $prenom1, $prenom2){
        $view = null;
        $view = $this->templating->render('AppBundle:Mailing:modifier_troc_owner.html.twig', array());
        if (!$view)
            return false;
        
        // sujet
        $subject = "[TrocWine] #".$refTroc." - Modification du troc proposé";
        
        // variables dynamiques
        $lien_acces_espace = $this->app_front_url.$this->router->generate('front_accueil_connexion');
        $lien_acces_espace = str_replace('//homepage', '/homepage', $lien_acces_espace);
        $view = str_replace('#LIEN_ACCES_DIRECT#',$lien_acces_espace, $view);
        $view = str_replace('#PRENOM1#',$prenom1, $view);
        $view = str_replace('#PRENOM2#',$prenom2, $view);
                        
        return $this->sendMail($subject, $view, $to);
    }
    
    
    
    public function sendEmailRdvTroc($to, $refTroc, $prenom1, $prenom2, $adresse){
        $view = null;
        $view = $this->templating->render('AppBundle:Mailing:rdv_troc.html.twig', array());
        if (!$view)
            return false;
        
        // sujet
        $subject = "[TrocWine] #".$refTroc." - Lieu de rendez-vous proposé";
        
        // variables dynamiques
        $lien_acces_espace = $this->app_front_url.$this->router->generate('front_accueil_connexion');
        $lien_acces_espace = str_replace('//homepage', '/homepage', $lien_acces_espace);
        $view = str_replace('#LIEN_ACCES_DIRECT#',$lien_acces_espace, $view);
        $view = str_replace('#PRENOM1#',$prenom1, $view);
        $view = str_replace('#PRENOM2#',$prenom2, $view);
        $view = str_replace('#ADRESSE#',$adresse, $view);
                        
        return $this->sendMail($subject, $view, $to);
    }
    
    public function sendEmailMessageTroc($to, $refTroc, $prenom1, $prenom2){
        $view = null;
        $view = $this->templating->render('AppBundle:Mailing:message_troc.html.twig', array());
        if (!$view)
            return false;
        
        // sujet
        $subject = "[TrocWine] #".$refTroc." - Nouveau message";
        
        // variables dynamiques
        $lien_acces_espace = $this->app_front_url.$this->router->generate('front_accueil_connexion');
        $lien_acces_espace = str_replace('//homepage', '/homepage', $lien_acces_espace);
        $view = str_replace('#LIEN_ACCES_DIRECT#',$lien_acces_espace, $view);
        $view = str_replace('#PRENOM1#',$prenom1, $view);
        $view = str_replace('#PRENOM2#',$prenom2, $view);
                        
        return $this->sendMail($subject, $view, $to);
    }
    
    
    
    public function sendEmailAbandonTroc($to, $refTroc, $prenom1, $prenom2){       
        $view = null;
        $view = $this->templating->render('AppBundle:Mailing:abandon_troc.html.twig', array());
        if (!$view)
            return false;
        
        // sujet
        $subject = "[TrocWine] #".$refTroc." - Abandon troc proposé";
        
        // variables dynamiques
        $lien_acces_espace = $this->app_front_url.$this->router->generate('front_accueil_connexion');
        $lien_acces_espace = str_replace('//homepage', '/homepage', $lien_acces_espace);
        $view = str_replace('#LIEN_ACCES_DIRECT#',$lien_acces_espace, $view);
        $view = str_replace('#PRENOM1#',$prenom1, $view);
        $view = str_replace('#PRENOM2#',$prenom2, $view);
        
        $view = str_replace('#REF#','#'.$refTroc, $view);
        
                        
        return $this->sendMail($subject, $view, $to);
    }
    
    public function sendEmailFinTroc($toA, $toB, $refTroc){
        return true;
    }
    

    private function sendMail($subject, $view, $to){
        
        $view = $this->createOnlineVersion($view);
        
        // pour utiliser la fonction php mail à la place du smtp
        $transport = \Swift_MailTransport::newInstance();
        $this->mailer = \Swift_Mailer::newInstance($transport);
        
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
