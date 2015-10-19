<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\Type\SearchBouteilleType;
use AppBundle\Form\Type\SearchTroqueurType;

class RechercheController extends Controller
{
    /**
     * @Route("/search/bottle",name="front_search_bouteille")          
     */
    public function searchBottleAction(Request $request) {
        $bouteilles = array();
        $filtres = $request->request->get('SearchBouteille',null);
        
        $formBouteille = $this->createForm(new SearchBouteilleType(), null, array(
            'action' => $this->generateUrl('front_search_bouteille'),
            'method' => 'POST',
        ));
        $formBouteille->add('submit', 'submit', array('label' => 'Rechercher'));   
               
        $formTroqueur = $this->createForm(new SearchTroqueurType(), null, array(
            'action' => $this->generateUrl('front_search_troqueur'),
            'method' => 'POST',
        ));
        $formTroqueur->add('submit', 'submit', array('label' => 'Rechercher')); 
        
        $em = $this->getDoctrine()->getManager();
        $bouteilles = $em->getRepository('AppBundle:Bouteille')->findFromSelector($filtres);
        
        return $this->render("AppBundle:Recherche:search_bottle.html.twig", array(                
                    'formBouteille'   => $formBouteille->createView(),
                    'formTroqueur'   => $formTroqueur->createView(),
                    'filtres'=> $filtres,
                    'bouteilles'=> $bouteilles,
                    'panelOpen' => 'bouteille'
        ));
    }
    
    /**
     * @Route("/search/troqueur",name="front_search_troqueur")          
     */
    public function searchTroqueurAction(Request $request) {
        $troqueurs = array();
        $filtres = $request->request->get('SearchTroqueur',null);
        
        $formBouteille = $this->createForm(new SearchBouteilleType(), null, array(
            'action' => $this->generateUrl('front_search_bouteille'),
            'method' => 'POST',
        ));
        $formBouteille->add('submit', 'submit', array('label' => 'Rechercher'));                
        
        $formTroqueur = $this->createForm(new SearchTroqueurType(), null, array(
            'action' => $this->generateUrl('front_search_troqueur'),
            'method' => 'POST',
        ));
        $formTroqueur->add('submit', 'submit', array('label' => 'Rechercher'));                
        
        return $this->render("AppBundle:Recherche:search_troqueur.html.twig", array(                
            'formBouteille'   => $formBouteille->createView(),
            'formTroqueur'   => $formTroqueur->createView(),
            'filtres'=> $filtres,
            'troqueurs'=> $troqueurs,
            'panelOpen' => 'troqueur'
        ));
    }
}
