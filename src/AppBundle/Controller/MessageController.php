<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\FormError;

use AppBundle\Entity\TrocMessage;
use AppBundle\Entity\TrocSection;
use AppBundle\Entity\TrocRDV;
use AppBundle\Entity\AddressRdv;
use AppBundle\Entity\TrocTW;
use AppBundle\Form\Type\TrocAType;
use AppBundle\Form\Type\TrocBType;
use AppBundle\Form\Type\TrocMessageType;
use AppBundle\Form\Type\AddressRdvType;


use AppBundle\Entity\Bouteille;

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
        
        return $this->render('AppBundle:Messages:index.html.twig', array(
            'user' => $user,
            'trocArchive' => $archive,
            'messages' => $messages
        ));
    }
    
    
    /**
     * @Route("/messages/addressTW/get",name="front_messagerie_get_address_tw")          
     */
    public function getAddressTWAction(Request $request) {
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) { throw $this->createNotFoundException('Accès impossible.'); }
        
        $em = $this->getDoctrine()->getManager();
        
        $id = $request->get('id',0);
        
        $entity = $em->getRepository('AppBundle:AddressTW')->find($id);
        if(!$entity){ throw $this->createNotFoundException('Address TW inconnue.'); }
        
        $addressForMap = $entity->getStreet().', '.$entity->getZipCode().', '.$entity->getCity().', '.$entity->getCountry();
        
        return $this->render('AppBundle:Messages:addressForMap.html.twig', array(
            'addressForMap' => $addressForMap,
            ));
        
    }
    
    /**
     * @Route("/messages/gestion/{id}",name="front_messagerie_gestion")          
     */
    public function gestionAction(Request $request, $id) {
        
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) { throw $this->createNotFoundException('Accès impossible.'); }
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$user){ throw $this->createNotFoundException('Accès impossible.'); }
        
        $em = $this->getDoctrine()->getManager();
        
        $troc = $em->getRepository('AppBundle:Troc')->find($id);
        if(!$troc){ throw $this->createNotFoundException('Troc inconnu.'); }
        if($troc->getMemberA() != $user && $troc->getMemberB() != $user){ throw $this->createNotFoundException('Accès impossible.'); }
        
        if($troc->getArchived()){ throw $this->createNotFoundException('Action impossible. Le troc est déjà terminé.'); }
        
        $formFinTroc = $this->generateFormForMemberAorB($troc, $user, $id);                
        
        $trocMessage = new TrocMessage();
        $formMessage = $this->generateFormMessage($trocMessage, $id);
        
        $addressRDV = new AddressRdv();
        $formRDV = $this->generateFormRdv($addressRDV, $id);
        
        $formTrocTW = $this->generateFormTrocTw($id);
        
        
        if($troc->getMemberA() == $user){
            $prochesVous = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberA(), $troc->getMemberB());
            $prochesLui = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberB(), $troc->getMemberA());
        }else{
            $prochesVous = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberB(), $troc->getMemberA());
            $prochesLui = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberA(), $troc->getMemberB());
        }
        $dansRegions = $em->getRepository('AppBundle:AddressTW')->findInRegionsAandB($troc->getMemberA(), $troc->getMemberB());
        
        return $this->render('AppBundle:Messages:message.html.twig', array(
            'user' => $user,
            'troc' => $troc,
            'trocArchive' => $troc->getArchived(),
            'formFinTroc' => $formFinTroc->createView(),
            'formMessage' => $formMessage->createView(),
            'formRDV' => $formRDV->createView(),
            'formTrocTW' => $formTrocTW->createView(),
            'prochesVous' => $prochesVous,
            'prochesLui' => $prochesLui,
            'dansRegions' => $dansRegions,
        ));
    }
    
    /**
     * @Route("/messages/gestion/{id}/addMessage",name="front_messagerie_gestion_add_message")          
     */
    public function addMessageAction(Request $request, $id) {
        
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) { throw $this->createNotFoundException('Accès impossible.'); }
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$user){ throw $this->createNotFoundException('Accès impossible.'); }
        
        $em = $this->getDoctrine()->getManager();
        
        $troc = $em->getRepository('AppBundle:Troc')->find($id);
        if(!$troc){ throw $this->createNotFoundException('Troc inconnu.'); }
        if($troc->getMemberA() != $user && $troc->getMemberB() != $user){ throw $this->createNotFoundException('Accès impossible.'); }
        
        if($troc->getArchived()){ throw $this->createNotFoundException('Action impossible. Le troc est déjà terminé.'); }
        
        if($troc->getMemberA() == $user){
            $troqueur = $troc->getMemberB();
        }else{
            $troqueur = $troc->getMemberA();
        }
        
        $emailTroqueur = $troqueur->getEmail(); 
        
        $refTroc = $troc->getId();
        
        $formFinTroc = $this->generateFormForMemberAorB($troc, $user, $id);
        
        $trocMessage = new TrocMessage();
        $formMessage = $this->generateFormMessage($trocMessage, $id);
        
        $addressRDV = new AddressRdv();
        $formRDV = $this->generateFormRdv($addressRDV, $id);
                
        $formTrocTW = $this->generateFormTrocTw($id);
        
        
        if($troc->getMemberA() == $user){
            $prochesVous = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberA(), $troc->getMemberB());
            $prochesLui = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberB(), $troc->getMemberA());
        }else{
            $prochesVous = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberB(), $troc->getMemberA());
            $prochesLui = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberA(), $troc->getMemberB());
        }
        $dansRegions = $em->getRepository('AppBundle:AddressTW')->findInRegionsAandB($troc->getMemberA(), $troc->getMemberB());
        
        
        
        // traitement message
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
            
            if (!$this->get('mail_to_user')->sendEmailMessageTroc($emailTroqueur,$refTroc, $troqueur->getFirstname(), $user->getFirstname())) {
                throw $this->createNotFoundException('Unable to send message troc mail.');
            } 
            
            return $this->redirectToRoute('front_messagerie_gestion', ['id' => $troc->getId()]);
        }
        
        return $this->render('AppBundle:Messages:message.html.twig', array(
            'user' => $user,
            'troc' => $troc,
            'trocArchive' => $troc->getArchived(),
            'formFinTroc' => $formFinTroc->createView(),
            'formMessage' => $formMessage->createView(),
            'formRDV' => $formRDV->createView(),
            'formTrocTW' => $formTrocTW->createView(),
            'prochesVous' => $prochesVous,
            'prochesLui' => $prochesLui,
            'dansRegions' => $dansRegions,
        ));
    }
    
    /**
     * @Route("/messages/gestion/{id}/addRdv",name="front_messagerie_gestion_add_rdv")          
     */
    public function addRdvAction(Request $request, $id) {
        
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) { throw $this->createNotFoundException('Accès impossible.'); }
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$user){ throw $this->createNotFoundException('Accès impossible.'); }
        
        $em = $this->getDoctrine()->getManager();
        
        $troc = $em->getRepository('AppBundle:Troc')->find($id);
        if(!$troc){ throw $this->createNotFoundException('Troc inconnu.'); }
        if($troc->getMemberA() != $user && $troc->getMemberB() != $user){ throw $this->createNotFoundException('Accès impossible.'); }
        
        if($troc->getArchived()){ throw $this->createNotFoundException('Action impossible. Le troc est déjà terminé.'); }
        
        if($troc->getMemberA() == $user){
            $troqueur = $troc->getMemberB();
        }else{
            $troqueur = $troc->getMemberA();
        }
        
        $emailTroqueur = $troqueur->getEmail(); 
        
        $refTroc = $troc->getId();
        
        $formFinTroc = $this->generateFormForMemberAorB($troc, $user, $id);
        
        $trocMessage = new TrocMessage();
        $formMessage = $this->generateFormMessage($trocMessage, $id);
        
        $addressRDV = new AddressRdv();
        $formRDV = $this->generateFormRdv($addressRDV, $id);
        
        $formTrocTW = $this->generateFormTrocTw($id);
        
        
        if($troc->getMemberA() == $user){
            $prochesVous = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberA(), $troc->getMemberB());
            $prochesLui = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberB(), $troc->getMemberA());
        }else{
            $prochesVous = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberB(), $troc->getMemberA());
            $prochesLui = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberA(), $troc->getMemberB());
        }
        $dansRegions = $em->getRepository('AppBundle:AddressTW')->findInRegionsAandB($troc->getMemberA(), $troc->getMemberB());
        
        
        
        // traitement message
        $formRDV->handleRequest($request);
        if ($formRDV->isValid()) {
            $trocSection = new TrocSection();
            //$em->persist($addressRDV);
            
            $trocRdv = new TrocRDV();
            $trocRdv->setSuggested(0);
            $trocRdv->addAddressRdv($addressRDV);
            $em->persist($trocRdv);
            
            $addressRDV->setTrocRdv($trocRdv);
                        
            $trocSection->setTroc($troc);   
            $trocSection->setRdv($trocRdv);
            $trocSection->setMember($user);
            $em->persist($trocSection);

            $troc->addTrocSection($trocSection);
            $em->persist($troc);

            $em->flush();
            
            $adresse = $addressRDV->getStreet().' - '.$addressRDV->getZipCode().' '.$addressRDV->getCity();            
            
            if (!$this->get('mail_to_user')->sendEmailRdvTroc($emailTroqueur,$refTroc, $troqueur->getFirstname(), $user->getFirstname(), $adresse)) {
                throw $this->createNotFoundException('Unable to send rendez-vous troc mail.');
            } 
            
            return $this->redirectToRoute('front_messagerie_gestion', ['id' => $troc->getId()]);
        }
        
        return $this->render('AppBundle:Messages:message.html.twig', array(
            'user' => $user,
            'troc' => $troc,
            'trocArchive' => $troc->getArchived(),
            'formFinTroc' => $formFinTroc->createView(),
            'formMessage' => $formMessage->createView(),
            'formRDV' => $formRDV->createView(),
            'formTrocTW' => $formTrocTW->createView(),
            'prochesVous' => $prochesVous,
            'prochesLui' => $prochesLui,
            'dansRegions' => $dansRegions,
        ));
    }
    
    /**
     * @Route("/messages/gestion/{id}/addRdvTW",name="front_messagerie_gestion_add_rdv_tw")          
     */
    public function addRdvTWAction(Request $request, $id) {
        
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) { throw $this->createNotFoundException('Accès impossible.'); }
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$user){ throw $this->createNotFoundException('Accès impossible.'); }
        
        $em = $this->getDoctrine()->getManager();
        
        $troc = $em->getRepository('AppBundle:Troc')->find($id);
        if(!$troc){ throw $this->createNotFoundException('Troc inconnu.'); }
        if($troc->getMemberA() != $user && $troc->getMemberB() != $user){ throw $this->createNotFoundException('Accès impossible.'); }
        
        if($troc->getArchived()){ throw $this->createNotFoundException('Action impossible. Le troc est déjà terminé.'); }
        
        if($troc->getMemberA() == $user){
            $troqueur = $troc->getMemberB();
        }else{
            $troqueur = $troc->getMemberA();
        }
        
        $emailTroqueur = $troqueur->getEmail(); 
        
        $refTroc = $troc->getId();
        
        $formFinTroc = $this->generateFormForMemberAorB($troc, $user, $id);
        
        $trocMessage = new TrocMessage();
        $formMessage = $this->generateFormMessage($trocMessage, $id);
        
        $addressRDV = new AddressRdv();
        $formRDV = $this->generateFormRdv($addressRDV, $id);
        
        $formTrocTW = $this->generateFormTrocTw($id);
        
        
        if($troc->getMemberA() == $user){
            $prochesVous = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberA(), $troc->getMemberB());
            $prochesLui = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberB(), $troc->getMemberA());
        }else{
            $prochesVous = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberB(), $troc->getMemberA());
            $prochesLui = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberA(), $troc->getMemberB());
        }
        $dansRegions = $em->getRepository('AppBundle:AddressTW')->findInRegionsAandB($troc->getMemberA(), $troc->getMemberB());
        
        
        
        // traitement message
        $formTrocTW->handleRequest($request);
        if ($formTrocTW->isValid()) {
            $data = $formTrocTW->getData();
            $addressTW = $em->getRepository('AppBundle:AddressTW')->find($data['idaddresstw']);
            
            if(!$addressTW){ throw $this->createNotFoundException('Address TW inconnue.'); }
            
            $trocSection = new TrocSection();
            //$em->persist($addressRDV);
            
            $trocTW = new TrocTW();
            $trocTW->setSuggested(1);
            $trocTW->setAddressTW($addressTW);
            $em->persist($trocTW);
                        
            $trocSection->setTroc($troc);   
            $trocSection->setRdvTW($trocTW);
            $trocSection->setMember($user);
            $em->persist($trocSection);

            $troc->addTrocSection($trocSection);
            $em->persist($troc);

            $em->flush();
            
            $adresse = $addressTW->getStreet().' - '.$addressTW->getZipCode().' '.$addressTW->getCity();            
            
            if (!$this->get('mail_to_user')->sendEmailRdvTroc($emailTroqueur,$refTroc, $troqueur->getFirstname(), $user->getFirstname(), $adresse)) {
                throw $this->createNotFoundException('Unable to send rendez-vous troc mail.');
            } 
            
            return $this->redirectToRoute('front_messagerie_gestion', ['id' => $troc->getId()]);
        }
        
        return $this->render('AppBundle:Messages:message.html.twig', array(
            'user' => $user,
            'troc' => $troc,
            'trocArchive' => $troc->getArchived(),
            'formFinTroc' => $formFinTroc->createView(),
            'formMessage' => $formMessage->createView(),
            'formRDV' => $formRDV->createView(),
            'formTrocTW' => $formTrocTW->createView(),
            'prochesVous' => $prochesVous,
            'prochesLui' => $prochesLui,
            'dansRegions' => $dansRegions,
        ));
    }
    
    /**
     * @Route("/messages/gestion/{id}/fin",name="front_messagerie_gestion_fin")          
     */
    public function finMessageAction(Request $request, $id) {
        
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) { throw $this->createNotFoundException('Accès impossible.'); }
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$user){ throw $this->createNotFoundException('Accès impossible.'); }
        
        $em = $this->getDoctrine()->getManager();
        
        $troc = $em->getRepository('AppBundle:Troc')->find($id);
        if(!$troc){ throw $this->createNotFoundException('Troc inconnu.'); }
        if($troc->getMemberA() != $user && $troc->getMemberB() != $user){ throw $this->createNotFoundException('Accès impossible.'); }
        
        if($troc->getArchived()){ throw $this->createNotFoundException('Action impossible. Le troc est déjà terminé.'); }
        
        if($troc->getMemberA() == $user){
            $troqueur = $troc->getMemberB();
        }else{
            $troqueur = $troc->getMemberA();
        }
                
                
        $formFinTroc = $this->generateFormForMemberAorB($troc, $user, $id);
        
        $trocMessage = new TrocMessage();
        $formMessage = $this->generateFormMessage($trocMessage, $id);
        
        $addressRDV = new AddressRdv();
        $formRDV = $this->generateFormRdv($addressRDV, $id);
        
        $trocTW = new TrocTW();
        $formTrocTW = $this->generateFormTrocTw($id);
        
        
        if($troc->getMemberA() == $user){
            $prochesVous = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberA(), $troc->getMemberB());
            $prochesLui = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberB(), $troc->getMemberA());
        }else{
            $prochesVous = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberB(), $troc->getMemberA());
            $prochesLui = $em->getRepository('AppBundle:AddressTW')->findNearAwithB($troc->getMemberA(), $troc->getMemberB());
        }
        $dansRegions = $em->getRepository('AppBundle:AddressTW')->findInRegionsAandB($troc->getMemberA(), $troc->getMemberB());
        
        
        // traitement fin troc
        $formFinTroc->handleRequest($request);
        if ($formFinTroc->isValid()) {       
            $quantityError = false;
            $sendMailDeFin = false;
            // si double validation alors finalisation terminée
            if($troc->getFinishedA() && $troc->getFinishedB()){
                $troc->setArchived(true);
                if($troc->getMemberA() == $user){
                    
                    $user->updateNote($troc->getNoteA());
                    $user->increaseTotalTroc();
                    
                    $troqueur->updateNote($troc->getNoteB());
                    $troqueur->increaseTotalTroc();
                    
                    $userBouteilles = $troc->getTrocSections()[0]->getContenu()->getTrocABouteilles();
                    $troqueurBouteilles = $troc->getTrocSections()[0]->getContenu()->getTrocBBouteilles();
                }else {
                    $user->updateNote($troc->getNoteB());
                    $user->increaseTotalTroc();
                    
                    $troqueur->updateNote($troc->getNoteA());
                    $troqueur->increaseTotalTroc();
                    
                    $userBouteilles = $troc->getTrocSections()[0]->getContenu()->getTrocBBouteilles();
                    $troqueurBouteilles = $troc->getTrocSections()[0]->getContenu()->getTrocABouteilles();
                }
                
                $trocContenu = $troc->getTrocSections()[0]->getContenu();

                $MessageError = "";

                // on vérifie si quantité toujours disponible pour conclure le troc
                foreach($userBouteilles as $bouteille){
                    if($bouteille->getBouteille()->getQuantite() < $bouteille->getQuantite()){
                        $quantityError = true;
                        $MessageError = "Vous n'avez plus assez de quantité pour assurer votre Troc.";
                        break;
                    }
                }
                foreach($troqueurBouteilles as $bouteille){
                    if($bouteille->getBouteille()->getQuantite() < $bouteille->getQuantite()){
                        $quantityError = true;
                        $MessageError = "Le troqueur n'a plus assez de quantité pour assurer le Troc.";
                        break;
                    }
                }
                if(!$quantityError){
                    // attribue les bouteilles si addToCave
                    
                    if($troc->getAddToCaveA()){
                        foreach($troqueurBouteilles as $bouteille){
                            if($bouteille->getBouteille()->getQuantite() > $bouteille->getQuantite()){
                                $bouteille->getBouteille()->setQuantite($bouteille->getBouteille()->getQuantite() - $bouteille->getQuantite());

                                $newBouteille = clone $bouteille->getBouteille();
                                //$newBouteille->clear();
                                $newBouteille->setMember($user);
                                $newBouteille->setQuantite($bouteille->getQuantite());
                                $newBouteille->setReserved(false);

                                $newImage = clone $bouteille->getBouteille()->getPhoto();
                                if($newImage){
                                    //$newImage->clear();
                                    $em->persist($newImage);
                                    $newBouteille->setPhoto($newImage);
                                }                                    
                                $em->persist($newBouteille);                                    
                                $em->persist($bouteille->getBouteille());
                            }else{
                                $newBouteille = $bouteille->getBouteille();
                                $newBouteille->setMember($user);
                                $newBouteille->setReserved(false);
                                $em->persist($newBouteille);
                                // supprime les Trocs Lies
                                $this->supprimeTrocsLies($em, $bouteille->getBouteille(), $id);
                            }
                        }
                    }else{                        
                        // on les retire du site
                        foreach($troqueurBouteilles as $bouteille){
                            if($bouteille->getBouteille()->getQuantite() > $bouteille->getQuantite()){
                                $bouteille->getBouteille()->setQuantite($bouteille->getBouteille()->getQuantite() - $bouteille->getQuantite());
                                $em->persist($bouteille->getBouteille());
                            }else{  
                                // soit on supprime
                                
                                /*$bouteilleToDelete = $bouteille->getBouteille();
                                // on annule la liaison avec ce troc
                                if($troc->getMemberA() == $user){
                                    $bouteille->setBouteille(null);
                                    $bouteille->setTrocContenuB(null);
                                    $trocContenu->removeTrocBBouteille($bouteille);
                                }else{
                                    $bouteille->setBouteille(null);
                                    $bouteille->setTrocContenuA(null);
                                    $trocContenu->removeTrocABouteille($bouteille);
                                }
                                $em->persist($trocContenu);
                                // puis on supprime les autres Trocs Lies
                                $this->supprimeTrocsLies($em, $bouteille->getBouteille(), $id);
                                
                                // puis on les supprimes
                                $em->remove($bouteille);
                                $em->remove($bouteilleToDelete);                                 
                                 */
                                
                                // soit on retire juste l'appartenance à l'ancien propriétaire
                                $bouteilleToDelete = $bouteille->getBouteille();
                                $bouteilleToDelete->setMember(null);
                                $em->persist($bouteilleToDelete);
                            }
                        }
                    }
                    
                    if($troc->getAddToCaveB()){
                        foreach($userBouteilles as $bouteille){
                            if($bouteille->getBouteille()->getQuantite() > $bouteille->getQuantite()){
                                $bouteille->getBouteille()->setQuantite($bouteille->getBouteille()->getQuantite() - $bouteille->getQuantite());

                                $newBouteille = clone $bouteille->getBouteille();
                                //$newBouteille->clear();
                                $newBouteille->setMember($troqueur);
                                $newBouteille->setQuantite($bouteille->getQuantite());
                                $newBouteille->setReserved(false);

                                $newImage = clone $bouteille->getBouteille()->getPhoto();
                                if($newImage){
                                    //$newImage->clear();
                                    $em->persist($newImage);
                                    $newBouteille->setPhoto($newImage);
                                }                                    
                                $em->persist($newBouteille);                                    
                                $em->persist($bouteille->getBouteille());
                            }else{
                                $newBouteille = $bouteille->getBouteille();
                                $newBouteille->setMember($troqueur);
                                $newBouteille->setReserved(false);
                                $em->persist($newBouteille);
                                // supprime les Trocs Lies
                                $this->supprimeTrocsLies($em, $bouteille->getBouteille(), $id);
                            }
                        }
                    }else{
                        // on les retire du site
                        foreach($userBouteilles as $bouteille){
                            if($bouteille->getBouteille()->getQuantite() > $bouteille->getQuantite()){
                                $bouteille->getBouteille()->setQuantite($bouteille->getBouteille()->getQuantite() - $bouteille->getQuantite());
                                $em->persist($bouteille->getBouteille());
                            }else{    
                                // soit on supprime
                                
                                /*$bouteilleToDelete = $bouteille->getBouteille();                        
                                // on annule la liaison avec ce troc
                                if($troc->getMemberA() == $user){
                                    $bouteille->setBouteille(null);
                                    $bouteille->setTrocContenuB(null);
                                    $trocContenu->removeTrocBBouteille($bouteille);
                                }else{
                                    $bouteille->setBouteille(null);
                                    $bouteille->setTrocContenuA(null);
                                    $trocContenu->removeTrocABouteille($bouteille);
                                }
                                $em->persist($trocContenu);
                                // puis on supprime les autres Trocs Lies
                                $this->supprimeTrocsLies($em, $bouteille->getBouteille(), $id);
                                
                                // puis on les supprimes
                                $em->remove($bouteille);
                                $em->remove($bouteilleToDelete);                               
                                 */
                                
                                // soit on retire juste l'appartenance à l'ancien propriétaire
                                $bouteilleToDelete = $bouteille->getBouteille();
                                $bouteilleToDelete->setMember(null);
                                $em->persist($bouteilleToDelete);
                            }
                        }
                    }
                    

                    $em->persist($user);
                    $em->persist($troqueur);
                    $sendMailDeFin = true;
                }else{
                    if($troc->getMemberA() == $user){
                        $formFinTroc->get('finishedA')->addError(new FormError($MessageError));
                    }else{
                        $formFinTroc->get('finishedB')->addError(new FormError($MessageError));
                    }
                }
                
            }
            
            if(!$quantityError){
                $refTroc = $troc->getId();
                
                $trocSection = new TrocSection();       
                $trocMessage = new TrocMessage();
                $trocMessage->setMessage('###TROC_TERMINE###');
                $em->persist($trocMessage);
                $trocSection->setTroc($troc);   
                $trocSection->setMessage($trocMessage);
                $trocSection->setMember(null);
                $em->persist($trocSection);

                $troc->addTrocSection($trocSection);
                $em->persist($troc);
                
                
                $em->persist($troc);

                $em->flush();
                
                if($sendMailDeFin){
                    if (!$this->get('mail_to_user')->sendEmailFinTroc($troc->getMemberA()->getEmail(),$troc->getMemberB()->getEmail(),$refTroc)) {
                        throw $this->createNotFoundException('Unable to send fin troc mail.');
                    }
                    // les 2 ont finalisé le troc alors on renvoi vers messagerie
                    return $this->redirectToRoute('front_messagerie');
                }
                // un seul a finalisé le troc ? alors on réaffiche la gestion pour lire le message fin de troc
                return $this->redirectToRoute('front_messagerie_gestion', ['id' => $troc->getId()]);
            }
        }
        
        return $this->render('AppBundle:Messages:message.html.twig', array(
            'user' => $user,
            'troc' => $troc,
            'trocArchive' => $troc->getArchived(),
            'formFinTroc' => $formFinTroc->createView(),
            'formMessage' => $formMessage->createView(),
            'formRDV' => $formRDV->createView(),
            'formTrocTW' => $formTrocTW->createView(),
            'prochesVous' => $prochesVous,
            'prochesLui' => $prochesLui,
            'dansRegions' => $dansRegions,
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
    
    
    private function generateFormMessage($trocMessage, $id){
        $lang = $this->get('session')->get('_locale', 'fr');
        $formMessage =  $this->createForm(new TrocMessageType($lang), $trocMessage, array(
            'action' => $this->generateUrl('front_messagerie_gestion_add_message', array('id' => $id)),
            'method' => 'POST',
        ));        
        return $formMessage;
    }
    
    private function generateFormForMemberAorB($troc, $user, $id){        
        $lang = $this->get('session')->get('_locale', 'fr');
        if($troc->getMemberA() == $user){            
            $formFinTroc = $this->createForm(new TrocAType($lang), $troc, array(
                'action' => $this->generateUrl('front_messagerie_gestion_fin', array('id' => $id)),
                'method' => 'POST',
            ));
        }else{
            $formFinTroc = $this->createForm(new TrocBType($lang), $troc, array(
                'action' => $this->generateUrl('front_messagerie_gestion_fin', array('id' => $id)),
                'method' => 'POST',
            ));
        }        
        return $formFinTroc;
    }
    
    private function generateFormRdv($addressRDV, $id){ 
        $lang = $this->get('session')->get('_locale', 'fr');
        $formRDV =  $this->createForm(new AddressRdvType($lang), $addressRDV, array(
            'action' => $this->generateUrl('front_messagerie_gestion_add_rdv', array('id' => $id)),
            'method' => 'POST',
        ));        
        return $formRDV;
    }
    
    private function generateFormTrocTw($id){   
        $data = array();
        $formTrocTW = $this->createFormBuilder($data, array(
                'action' => $this->generateUrl('front_messagerie_gestion_add_rdv_tw', array('id' => $id)),
                'method' => 'POST',
            ))
            ->add('idaddresstw', 'hidden', ['required'=>true])
            ->getForm();
        return $formTrocTW;
    }
    
    private function supprimeTrocsLies($em, $bouteille, $id)
    {
        // TODO : on retire la bouteille des trocs reliés
        $otherTrocs = $em->getRepository('AppBundle:Troc')->findTrocLiesToBottle($bouteille, $id);
        if($otherTrocs){
            foreach($otherTrocs as $troc){
                
                $trocContenu = $troc->getTrocSections()[0]->getContenu();
                
                $choixA = $trocContenu->getTrocABouteilles();
                $choixB = $trocContenu->getTrocBBouteilles();                
                foreach($choixA as $choix){
                    if($choix->getBouteille() == $bouteille){
                        $bouteille->setBouteille(null);
                        $choix->setTrocContenuA(null);
                        $trocContenu->removeTrocABouteille($choix);
                        $em->remove($choix);
                    }
                }
                foreach($choixB as $choix){
                    if($choix->getBouteille() == $bouteille){
                        $bouteille->setBouteille(null);
                        $choix->setTrocContenuB(null);
                        $trocContenu->removeTrocBBouteille($choix);
                        $em->remove($choix);
                    }
                }
                $em->persist($trocContenu);
            }
            
            $trocSection = new TrocSection();       
            $trocMessage = new TrocMessage();
            $trocMessage->setMessage('###TROC_BOTTLE_REMOVED###');
            $em->persist($trocMessage);
            $trocSection->setTroc($troc);   
            $trocSection->setMessage($trocMessage);
            $trocSection->setMember(null);
            $em->persist($trocSection);

            $troc->addTrocSection($trocSection);
            $em->persist($troc);
        }
        
    }
    
    
    /**
     * @Route("/messages/gestion/{id}/abandon",name="front_messagerie_gestion_abandon")          
     */
    public function abandonMessageAction(Request $request, $id) {
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) { throw $this->createNotFoundException('Accès impossible.'); }
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$user){ throw $this->createNotFoundException('Accès impossible.'); }
        
        $em = $this->getDoctrine()->getManager();
        
        $troc = $em->getRepository('AppBundle:Troc')->find($id);
        if(!$troc){ throw $this->createNotFoundException('Troc inconnu.'); }
        if($troc->getMemberA() != $user && $troc->getMemberB() != $user){ throw $this->createNotFoundException('Accès impossible.'); }
        
        if($troc->getArchived()){ throw $this->createNotFoundException('Action impossible. Le troc est déjà terminé.'); }
                
        if($troc->getMemberA() == $user){
            $troqueur = $troc->getMemberB();
        }else{
            $troqueur = $troc->getMemberA();
        }
        
        $emailTroqueur = $troqueur->getEmail(); 
        
        $refTroc = $troc->getId();
        
        foreach($troc->getTrocSections() as $trocSection){            
            if($trocSection->getMessage()){
                //$trocSection->getMessage()->setTrocSection(null);    
                $trocSection->setMessage(null);
            }
            if($trocSection->getRdv()){   
                $trocSection->setRdv(null);
            }
            $em->remove($trocSection);
        }
        
        $em->remove($troc);
        $em->flush();
        
        
        if (!$this->get('mail_to_user')->sendEmailAbandonTroc($emailTroqueur,$refTroc, $troqueur->getFirstname(), $user->getFirstname())) {
            throw $this->createNotFoundException('Unable to send abandon troc mail.');
        }        

        return $this->redirectToRoute('front_messagerie');
    }
    
}
