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
        $filtres = $request->request->get('SearchBouteille',null);  
        return $this->lanceRechercheBouteille($filtres);
    }
    
    /**
     * @Route("/search/bottle/{reference}",name="front_search_bouteille_ref_region")          
     */
    public function searchBottleRegionReferenceAction(Request $request, $reference) {
        $filtres = array();
        if($reference == 'others-regions'){
            $filtres['typeRegion'] = -1;
            $filtres['typeRegionAExclure'] = $this->getRegionAExclure(array('bordeaux','bourgogne','champagne'));
        }else{
            $filtres['typeRegion'] = $this->getRegionIdByReference($reference);        
        }
        $filtres['_token'] = 'aCtIvErEsUlTs';
        return $this->lanceRechercheBouteille($filtres);
    }
    
    private function getRegionIdByReference($reference){
        $em = $this->getDoctrine()->getManager();
        $idRegion = 0;
        $region = $em->getRepository('AppBundle:TypeRegion')->findBy(array('reference'=>$reference));
        if($region[0]){
            $idRegion = $region[0]->getId();
        }
        return $idRegion;
    }
    private function getRegionAExclure($references){
        $em = $this->getDoctrine()->getManager();
        $idRegion = "0";
        $regions = $em->getRepository('AppBundle:TypeRegion')->findRefAExclure($references);
        if($regions){
            foreach($regions as $region){
                $idRegion .= ','.$region->getId();
            }
        }
        return $idRegion;
    }
    
    
    private function lanceRechercheBouteille($filtres) {
        
        $idUserToFilter = 0;
        if ($this->get('security.context')->isGranted('ROLE_USER') ) {
            $user = $this->container->get('security.context')->getToken()->getUser();
            if($user){ $idUserToFilter = $user->getId(); }
        }
        
        $em = $this->getDoctrine()->getManager();
        $bouteilles = array();
        
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
        
        $withResults = false;
        if(isset($filtres['_token'])){
            $withResults = true;        
            $bouteilles = $em->getRepository('AppBundle:Bouteille')->findFromSelectorWithoutUser($filtres,$idUserToFilter);
        }
        
        return $this->render("AppBundle:Recherche:search_bottle.html.twig", array(                
                    'formBouteille'   => $formBouteille->createView(),
                    'formTroqueur'   => $formTroqueur->createView(),
                    'filtres'=> $filtres,
                    'withResults' => $withResults,
                    'bouteilles'=> $bouteilles,
                    'panelOpen' => 'bouteille'
        ));
    }
    
    /**
     * @Route("/search/ajax/bottle",name="front_search_bouteille_ajax")          
     */
    public function searchBottleAjaxAction(Request $request) {
        $filtres = $request->request->get('SearchBouteille',null);   
        
        if(isset($filtres['typeRegionAExclure'])&&($filtres['typeRegion'] == null)){
            $filtres['typeRegion'] = -1;
            $filtres['typeRegionAExclure'] = $this->getRegionAExclure(array('bordeaux','bourgogne','champagne'));
        }else{
            if(isset($filtres['typeRegionAExclure'])){
                unset($filtres['typeRegionAExclure']);
            }
        }
        
        return $this->lanceRechercheBouteilleAjax($filtres);
    }
    
    private function lanceRechercheBouteilleAjax($filtres) {
        
        $idUserToFilter = 0;
        if ($this->get('security.context')->isGranted('ROLE_USER') ) {
            $user = $this->container->get('security.context')->getToken()->getUser();
            if($user){ $idUserToFilter = $user->getId(); }
        }
        
        $em = $this->getDoctrine()->getManager();
        $bouteilles = array();
        
        $withResults = true;
        $bouteilles = $em->getRepository('AppBundle:Bouteille')->findFromSelectorWithoutUser($filtres,$idUserToFilter);
        
        return $this->render("AppBundle:Recherche:listing_bottle.html.twig", array(                
                    'withResults' => $withResults,
                    'bouteilles'=> $bouteilles                    
        ));
    }
    
    /**
     * @Route("/search/troqueur",name="front_search_troqueur")          
     */
    public function searchTroqueurAction(Request $request) {
        $idUserToFilter = 0;
        if ($this->get('security.context')->isGranted('ROLE_USER') ) {
            $user = $this->container->get('security.context')->getToken()->getUser();
            if($user){ $idUserToFilter = $user->getId(); }
        }
        
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
        
        $em = $this->getDoctrine()->getManager();
        $regionLike = '';
        if(isset($filtres['region'])){
            $regionTmp = $em->getRepository('AppBundle:Address')->find($filtres['region']);
            if($regionTmp){
                    if($regionTmp->getRegion() != null){
                    $regionLike = $regionTmp->getRegion();
                }
            }
        }
        
        $withResults = false;
        if(isset($filtres['_token'])){
            $withResults = true;        
            $troqueurs = $em->getRepository('AppBundle:Member')->findFromSelectorWithoutUser($filtres,$idUserToFilter, $regionLike);
        }
        
        
        return $this->render("AppBundle:Recherche:search_troqueur.html.twig", array(                
            'formBouteille'   => $formBouteille->createView(),
            'formTroqueur'   => $formTroqueur->createView(),
            'filtres'=> $filtres,
            'withResults'=>$withResults,
            'troqueurs'=> $troqueurs,
            'panelOpen' => 'troqueur'
        ));
    }
    
    /**
     * @Route("/search/ajax/troqueur",name="front_search_troqueur_ajax")          
     */
    public function searchTroqueurAjaxAction(Request $request) {
        $idUserToFilter = 0;
        if ($this->get('security.context')->isGranted('ROLE_USER') ) {
            $user = $this->container->get('security.context')->getToken()->getUser();
            if($user){ $idUserToFilter = $user->getId(); }
        }
        
        $troqueurs = array();
        $filtres = $request->request->get('SearchTroqueur',null);
        
        $em = $this->getDoctrine()->getManager();
        $regionLike = '';
        if(isset($filtres['region'])){
            $regionTmp = $em->getRepository('AppBundle:Address')->find($filtres['region']);
            if($regionTmp){
                    if($regionTmp->getRegion() != null){
                    $regionLike = $regionTmp->getRegion();
                }
            }
        }
        $withResults = true;
        $troqueurs = $em->getRepository('AppBundle:Member')->findFromSelectorWithoutUser($filtres,$idUserToFilter, $regionLike);
        
        return $this->render("AppBundle:Recherche:listing_troqueur.html.twig", array(            
                    'withResults' => $withResults,
                    'troqueurs'=> $troqueurs
        ));
    }
}
