<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Bouteille;

class TrocController extends Controller
{    
    
    /**
     * @Route("/troc/initialise/{idTroqueur}",name="front_troc_initialise")          
     */
    public function initialiseAction(Request $request, $idTroqueur) {
        
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) {
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$user){
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $troqueur = $em->getRepository('AppBundle:Member')->find($idTroqueur);        
        if(!$troqueur){
            throw $this->createNotFoundException('Troqueur inconnu.');
        }
        
        $preselectionMaCave = $request->request->get('selectionMaCave',null);
        $preselectionMaCave = json_decode($preselectionMaCave);
        $preselectionSaCave = $request->request->get('selectionSaCave',null);
        $preselectionSaCave = json_decode($preselectionSaCave);
        
        $mesBouteilles = $em->getRepository('AppBundle:Bouteille')->findBy(array('member' => $user));
        $sesBouteilles = $em->getRepository('AppBundle:Bouteille')->findBy(array('member' => $troqueur));
        
        return $this->render('AppBundle:Troc:initialise.html.twig', array(
            'user' => $user,
            'troqueur' => $troqueur,
            'mesBouteilles' => $mesBouteilles,
            'sesBouteilles' => $sesBouteilles,
            'preselectionMaCave' => $preselectionMaCave,
            'preselectionSaCave' => $preselectionSaCave
        ));
    }
    
    /**
     * @Route("/troc/proposer/{idTroqueur}",name="front_troc_proposer")          
     */
    public function proposerAction(Request $request, $idTroqueur) {
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) {
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$user){
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $troqueur = $em->getRepository('AppBundle:Member')->find($idTroqueur);        
        if(!$troqueur){
            throw $this->createNotFoundException('Troqueur inconnu.');
        }
        
        $preselectionMaCave = $request->request->get('selectionMaCave',null);
        $preselectionMaCave = json_decode($preselectionMaCave);
        $preselectionSaCave = $request->request->get('selectionSaCave',null);
        $preselectionSaCave = json_decode($preselectionSaCave);
        
        if(!$preselectionMaCave){
            throw $this->createNotFoundException('Sélection impossible.');
        }
        if(!$preselectionSaCave){
            throw $this->createNotFoundException('Sélection impossible.');
        }
        
        
        
    }
    
}
