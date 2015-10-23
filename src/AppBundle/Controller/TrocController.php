<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Troc;
use AppBundle\Entity\TrocSection;
use AppBundle\Entity\TrocContenu;
use AppBundle\Entity\TrocBouteille;
use AppBundle\Entity\TrocMessage;
use AppBundle\Entity\TrocRDV;

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
     * @Route("/troc/modify/{idTroc}",name="front_troc_modifier")          
     */
    public function modifieAction(Request $request, $idTroc) {
        
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) {
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$user){
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $troc = $em->getRepository('AppBundle:Troc')->find($idTroc);        
        if(!$troc){
            throw $this->createNotFoundException('Troc inconnu.');
        }
        
        if($troc->getArchived()){
            throw $this->createNotFoundException('Impossible Troc archivé.');
        }
        
        if($troc->getMemberA() == $user){
            $troqueur = $troc->getMemberB(); 
            
            $mesChoix = $troc->getTrocSections()[0]->getContenu()->getTrocABouteilles();
            $sesChoix = $troc->getTrocSections()[0]->getContenu()->getTrocBBouteilles();
            
        }else if($troc->getMemberB() == $user){
            $troqueur = $troc->getMemberA();  
            
            $mesChoix = $troc->getTrocSections()[0]->getContenu()->getTrocBBouteilles();
            $sesChoix = $troc->getTrocSections()[0]->getContenu()->getTrocABouteilles();       
        }
        
        if(!$troqueur){
            throw $this->createNotFoundException('Troqueur inconnu.');
        }
        
        $preselectionMaCave = array();
        $preselectionSaCave = array();
        
        foreach($mesChoix as $item){
            $preselectionMaCave[]=array('id'=>$item->getBouteille()->getId(), 'qte'=>$item->getQuantite());
        }
        foreach($sesChoix as $item){
            $preselectionSaCave[]=array('id'=>$item->getBouteille()->getId(), 'qte'=>$item->getQuantite());
        }
        
        $mesBouteilles = $em->getRepository('AppBundle:Bouteille')->findBy(array('member' => $user));
        $sesBouteilles = $em->getRepository('AppBundle:Bouteille')->findBy(array('member' => $troqueur));
        
        return $this->render('AppBundle:Troc:modifie.html.twig', array(
            'user' => $user,
            'troqueur' => $troqueur,
            'mesBouteilles' => $mesBouteilles,
            'sesBouteilles' => $sesBouteilles,
            'preselectionMaCave' => $preselectionMaCave,
            'preselectionSaCave' => $preselectionSaCave,
            'idTroc' => $idTroc
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
        
        $troc = new Troc();
        $troc->setMemberA($user);
        $troc->setMemberB($troqueur);        
        $em->persist($troc);
        
        $trocContenu = new TrocContenu();
        
        foreach($preselectionMaCave as $indice){
            $bouteille = new TrocBouteille();
            $boutRef = $em->getRepository('AppBundle:Bouteille')->find($indice->id);
            if($boutRef)
            {
                $bouteille->setBouteille($boutRef);
                $bouteille->setQuantite($indice->qte);
                $bouteille->setTrocContenuA($trocContenu);
                $em->persist($bouteille);
                $boutRef->addInTroc($bouteille);
                $em->persist($boutRef);
                $trocContenu->addTrocABouteille($bouteille);
            }
        }
        
        foreach($preselectionSaCave as $indice){
            $bouteille = new TrocBouteille();
            $boutRef = $em->getRepository('AppBundle:Bouteille')->find($indice->id);
            if($boutRef)
            {
                $bouteille->setBouteille($boutRef);
                $bouteille->setQuantite($indice->qte);
                $bouteille->setTrocContenuB($trocContenu);
                $em->persist($bouteille);
                $boutRef->addInTroc($bouteille);
                $em->persist($boutRef);
                $trocContenu->addTrocBBouteille($bouteille);
            }
        }                
        $em->persist($trocContenu);
                
        $trocSection = new TrocSection();       
        $trocSection->setTroc($troc);   
        $trocSection->setContenu($trocContenu);
        $trocSection->setMember($user);
        $em->persist($trocSection);
        
        $troc->addTrocSection($trocSection);
        $em->persist($troc);
        
        $em->flush();
        
        $refTroc = $troc->getId();
        
        if (!$this->get('mail_to_user')->sendEmailNouveauTroc($troqueur->getEmail(),$refTroc)) {
            throw $this->createNotFoundException('Unable to send new troc mail.');
        }
        if (!$this->get('mail_to_user')->sendEmailNouveauTrocOwner($user->getEmail(),$refTroc)) {
            throw $this->createNotFoundException('Unable to send new troc mail.');
        }
        
        return $this->redirectToRoute('front_messagerie_gestion', ['id' => $troc->getId()]);
    }
    
    /**
     * @Route("/troc/update/{idTroc}",name="front_troc_update")          
     */
    public function updateAction(Request $request, $idTroc) {
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) {
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$user){
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $troc = $em->getRepository('AppBundle:Troc')->find($idTroc);        
        if(!$troc){
            throw $this->createNotFoundException('Troc inconnu.');
        }
        
        if($troc->getArchived()){
            throw $this->createNotFoundException('Impossible Troc archivé.');
        }
        
        if($troc->getMemberA() == $user){
            $troqueur = $troc->getMemberB(); 
            
            $mesChoix = $troc->getTrocSections()[0]->getContenu()->getTrocABouteilles();
            $sesChoix = $troc->getTrocSections()[0]->getContenu()->getTrocBBouteilles();
            
        }else if($troc->getMemberB() == $user){
            $troqueur = $troc->getMemberA();  
            
            $mesChoix = $troc->getTrocSections()[0]->getContenu()->getTrocBBouteilles();
            $sesChoix = $troc->getTrocSections()[0]->getContenu()->getTrocABouteilles();       
        }
        
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
        
        
        if($troc->getMemberA() == $user){
            $troqueur = $troc->getMemberB(); 
            
            $mesChoix = $troc->getTrocSections()[0]->getContenu()->getTrocABouteilles();
            $sesChoix = $troc->getTrocSections()[0]->getContenu()->getTrocBBouteilles();
            
        }else if($troc->getMemberB() == $user){
            $troqueur = $troc->getMemberA();  
            
            $mesChoix = $troc->getTrocSections()[0]->getContenu()->getTrocBBouteilles();
            $sesChoix = $troc->getTrocSections()[0]->getContenu()->getTrocABouteilles();       
        }
        
        $oldTrocContenu = $troc->getTrocSections()[0]->getContenu();
        $em->remove($oldTrocContenu);
        
        $trocContenu = new TrocContenu();
        
        foreach($preselectionMaCave as $indice){
            $bouteille = new TrocBouteille();
            $boutRef = $em->getRepository('AppBundle:Bouteille')->find($indice->id);
            if($boutRef)
            {
                $bouteille->setBouteille($boutRef);
                $bouteille->setQuantite($indice->qte);
                $bouteille->setTrocContenuA($trocContenu);
                $em->persist($bouteille);
                $boutRef->addInTroc($bouteille);
                $em->persist($boutRef);
                $trocContenu->addTrocABouteille($bouteille);
            }
        }
        
        foreach($preselectionSaCave as $indice){
            $bouteille = new TrocBouteille();
            $boutRef = $em->getRepository('AppBundle:Bouteille')->find($indice->id);
            if($boutRef)
            {
                $bouteille->setBouteille($boutRef);
                $bouteille->setQuantite($indice->qte);
                $bouteille->setTrocContenuB($trocContenu);
                $em->persist($bouteille);
                $boutRef->addInTroc($bouteille);
                $em->persist($boutRef);
                $trocContenu->addTrocBBouteille($bouteille);
            }
        }                
        $em->persist($trocContenu);
                
        $trocSection = $troc->getTrocSections()[0];       
        $trocSection->setContenu($trocContenu);
        $trocSection->setMember($user);
        $em->persist($trocSection);
        
        
        $trocSection = new TrocSection();       
        $trocMessage = new TrocMessage();
        $trocMessage->setMessage('###NEW_TROC###');
        $em->persist($trocMessage);
        $trocSection->setTroc($troc);   
        $trocSection->setMessage($trocMessage);
        $trocSection->setMember($user);
        $em->persist($trocSection);
        
        $troc->addTrocSection($trocSection);
        $em->persist($troc);
        
        $em->flush();
        
        $refTroc = $troc->getId();
        
        if (!$this->get('mail_to_user')->sendEmailModifierTroc($troqueur->getEmail(),$refTroc)) {
            throw $this->createNotFoundException('Unable to send modifier troc mail.');
        }
        if (!$this->get('mail_to_user')->sendEmailModifierTrocOwner($user->getEmail(),$refTroc)) {
            throw $this->createNotFoundException('Unable to send modifier troc mail.');
        }
        
        return $this->redirectToRoute('front_messagerie_gestion', ['id' => $troc->getId()]);
    }
    
}
