<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\TrocMessage;
use AppBundle\Form\Type\TrocMessageType;
use AppBundle\Entity\TrocSection;

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
        
        $messages = $em->getRepository('AppBundle:Troc')->findByAllForUser($user, $archive);
        
        /*
        echo count($user->getBouteilles());
        
        \Symfony\Component\VarDumper\VarDumper::dump($messages);
        
        echo count($messages);
        
        echo count($messages[0]->getTrocSections()[0]->getContenu()->getTrocABouteilles());
        echo count($messages[0]->getTrocSections()[0]->getContenu()->getTrocBBouteilles());
        */
        return $this->render('AppBundle:Messages:index.html.twig', array(
            'user' => $user,
            'trocArchive' => $archive,
            'messages' => $messages
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
        
        $trocMessage = new TrocMessage();
        $formMessage = $this->createForm(new TrocMessageType(), null, array(
            'action' => $this->generateUrl('front_messagerie_gestion_add_message', array('id' => $id)),
            'method' => 'POST',
        ));
        
        return $this->render('AppBundle:Messages:message.html.twig', array(
            'user' => $user,
            'troc' => $troc,
            'trocArchive' => $troc->getArchived(),
            'formMessage' => $formMessage->createView(),
        ));
    }
    
    /**
     * @Route("/messages/gestion/{id}/addMessage",name="front_messagerie_gestion_add_message")          
     */
    public function addMessageAction(Request $request, $id) {
        
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
        
        $trocMessage = new TrocMessage();
        $formMessage = $this->createForm(new TrocMessageType(), $trocMessage, array(
            'action' => $this->generateUrl('front_messagerie_gestion_add_message', array('id' => $id)),
            'method' => 'POST',
        ));
        $formMessage->handleRequest($request);

        if ($formMessage->isValid()) {
            $trocSection = new TrocSection();
            $em->persist($trocMessage);
            $trocSection->setTroc($troc);   
            $trocSection->setMessage($trocMessage);
            $trocSection->setMember($user);
            $em->persist($trocSection);

            $troc->addTrocSection($trocSection);
            $em->persist($troc);

            $em->flush();
            return $this->redirectToRoute('front_messagerie_gestion', ['id' => $troc->getId()]);
        }
        
        return $this->render('AppBundle:Messages:message.html.twig', array(
            'user' => $user,
            'troc' => $troc,
            'trocArchive' => $troc->getArchived(),
            'formMessage' => $formMessage->createView(),
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
