<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/",name="front_accueil")          
     */
    public function indexAction() {
        return $this->render("AppBundle::index.html.twig");
    }
    
    /**
     * @Route("/notre-histoire",name="front_notre_histoire")          
     */
    public function notreHistoireAction() {
        return $this->render("AppBundle:Pages:notre_histoire.html.twig");
    }
    
    /**
     * @Route("/partenaires",name="front_partenaires")          
     */
    public function partenairesAction() {
        return $this->render("AppBundle:Pages:partenaires.html.twig");
    }
    
    /**
     * @Route("/contact",name="front_contact")          
     */
    public function contactAction() {
        return $this->render("AppBundle:Pages:contact.html.twig");
    }
    
    /**
     * @Route("/presse",name="front_presse")          
     */
    public function presseAction() {
        return $this->render("AppBundle:Pages:presse.html.twig");
    }
    
    /**
     * @Route("/mentions-legales",name="front_mentions_legales")          
     */
    public function mentionsLegalesAction() {
        return $this->render("AppBundle:Pages:mentions_legales.html.twig");
    }
    
    /**
     * @Route("/politique-de-cookies",name="front_politique_de_cookies")          
     */
    public function cookiesAction() {
        return $this->render("AppBundle:Pages:politique_de_cookies.html.twig");
    }
    
    /**
     * @Route("/mode-d-emploi",name="front_mode_emploi")          
     */
    public function modeDemploiAction() {
        return $this->render("AppBundle:Pages:mode_emploi.html.twig");
    }
}
