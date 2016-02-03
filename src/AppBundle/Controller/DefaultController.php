<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    
    /**
     * @Route("/",name="front_intro")          
     */
    public function indexAction() {
        
        return $this->render("AppBundle::index.html.twig", array());
    }
    
    /**
     * @Route("/homepage",name="front_accueil")          
     */
    public function homepageAction() {
        
        return $this->homepageGenerateAction(false);
    }
    
    /**
     * @Route("/homepage/connexion",name="front_accueil_connexion")          
     */
    public function homepageConnexionAction() {
        
        return $this->homepageGenerateAction(true);
    }
    
    
    private function homepageGenerateAction($connexion) {
        $em = $this->getDoctrine()->getManager();
        
        $meas = $em->getRepository('AppBundle:Mea')->findBy(array('actif'=>1),array('position'=>'ASC'));
        
        $selections = $em->getRepository('AppBundle:Selection')->findBy(array(),array('position'=>'ASC'));
        
        return $this->render("AppBundle::homepage.html.twig", array(
            'connexion'=>$connexion,
            'meas'=>$meas,
            'selections'=>$selections
        ));
    }
    
    /**
     * @Route("/toggle_lang/{lang}",name="toggle_lang")          
     */
    public function toggleLangAction($lang) {
        
        if(!in_array($lang, array('fr','en'))){            
            $lang = 'fr';
        }
        
        /*
        $this->get('request')->attributes->set('_locale', null);
        
        $request = $this->getRequest();

        // get last requested path
        $referer = $request->server->get('HTTP_REFERER');
        $lastPath = substr($referer, strpos($referer, $request->getBaseUrl()));
        $lastPath = str_replace($request->getBaseUrl(), '', $lastPath);

        // get last route
        $matcher = $this->get('router')->getMatcher();
        $parameters = $matcher->match($lastPath);

        // set new locale (to session and to the route parameters)
        //$parameters['_locale'] = $lang;
        $this->get('session')->set('_locale', $lang);

        // default parameters has to be unsetted!
        $route = $parameters['_route'];
        unset($parameters['_route']);
        unset($parameters['_controller']);

        return $this->redirect($this->generateUrl($route, $parameters));          
         */               
        
        
        if($lang != null)
        {
            // On enregistre la langue en session
            $this->get('session')->set('_locale', $lang);
        }

        // on tente de rediriger vers la page d'origine
        $url = $this->container->get('request')->headers->get('referer');
        if(empty($url))
        {
            $url = $this->container->get('router')->generate('front_intro');
        }

        return new RedirectResponse($url);
        
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
        $em = $this->getDoctrine()->getManager();
        
        $partenaires = $em->getRepository('AppBundle:Partenaire')->findBy(array('actif'=>1),array('position'=>'ASC'));
        
        return $this->render("AppBundle:Pages:partenaires.html.twig", array(
            'partenaires'=>$partenaires
        ));
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
        $em = $this->getDoctrine()->getManager();
        
        $presses = $em->getRepository('AppBundle:Presse')->findBy(array('actif'=>1),array('createdAt'=>'ASC'));
        
        return $this->render("AppBundle:Pages:presse.html.twig", array(
            'presses'=>$presses
        ));
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
