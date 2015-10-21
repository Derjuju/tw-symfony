<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends Controller
{    
    
    /**
     * @Route("/messages",name="front_messagerie")          
     */
    public function indexAction(Request $request) {
        
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) {
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$user){
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        return $this->listeMessagerie(false, $user);
    }
    
    /**
     * @Route("/messages/archives",name="front_messagerie_archives")          
     */
    public function archiveAction(Request $request) {
        
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) {
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$user){
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        return $this->listeMessagerie(true, $user);
    }
    
    private function listeMessagerie($archive, $user){
        
        $em = $this->getDoctrine()->getManager();
        
        
        
        return $this->render('AppBundle:Messages:index.html.twig', array(
            'user' => $user,
            'trocArchive' => $archive
        ));
    }
    
    
    /**
     * @Route("/messages/gestion/{id}",name="front_messagerie_gestion")          
     */
    public function gestionAction(Request $request, $id) {
        
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) {
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$user){
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $troc = $em->getRepository('AppBundle:Troc')->find($id);
        if(!$troc){
            throw $this->createNotFoundException('Troc inconnu.');
        }
        if($troc->getMemberA() != $user && $troc->getMemberB() != $user){
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        return $this->render('AppBundle:Messages:message.html.twig', array(
            'user' => $user,
            'troc' => $troc,
            'trocArchive' => $troc->getArchived()
        ));
    }
    
    /**
     * @Route("/messages/show/{id}",name="front_messagerie_show")          
     */
    public function showAction(Request $request, $id) {
        
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) {
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$user){
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $troc = $em->getRepository('AppBundle:Troc')->find($id);
        if(!$troc){
            throw $this->createNotFoundException('Troc inconnu.');
        }
        if($troc->getMemberA() != $user && $troc->getMemberB() != $user){
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        return $this->render('AppBundle:Messages:message.html.twig', array(
            'user' => $user,
            'troc' => $troc,
            'trocArchive' => $troc->getArchived()
        ));
    }
    
}
