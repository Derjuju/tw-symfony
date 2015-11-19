<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\Type\SearchBouteilleType;
use AppBundle\Form\Type\SearchTroqueurType;

class InterfaceController extends Controller
{
    
    public function searchSelectorAction() { 
        $request = $this->getRequest();
        return $this->searchSelectorConstruct(false,$request);
    }/*
    public function searchSelectorBouteilleAction() {     
        $request = $this->getRequest();
        return $this->searchSelectorConstruct('bouteille',$request);
    }
    public function searchSelectorTroqueurAction() {   
        $request = $this->getRequest();
        return $this->searchSelectorConstruct('troqueur',$request);
    }*/
    
    
    private function searchSelectorConstruct($panelOpen, Request $request) {
        
        $lang = $this->get('session')->get('_locale', 'fr');
        //$this->get('translator')->trans('Rechercher')
        
        $formBouteille = $this->createForm(new SearchBouteilleType($lang), null, array(
            'action' => $this->generateUrl('front_search_bouteille'),
            'method' => 'POST',
        ));
        $formBouteille->add('submit', 'submit', array('label' => 'Rechercher'));        
        $formBouteille->handleRequest($request);
        
        $formTroqueur = $this->createForm(new SearchTroqueurType($lang), null, array(
            'action' => $this->generateUrl('front_search_troqueur'),
            'method' => 'POST',
        ));
        $formTroqueur->add('submit', 'submit', array('label' => 'Rechercher'));        
        $formTroqueur->handleRequest($request);
        
        return $this->render("AppBundle:Interface:_search_selector.html.twig", array(
            'formBouteille'   => $formBouteille->createView(),
            'formTroqueur'   => $formTroqueur->createView(),
            'panelOpen' => $panelOpen
        ));
    }
}
