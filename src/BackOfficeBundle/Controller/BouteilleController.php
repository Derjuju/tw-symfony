<?php

namespace BackOfficeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Bouteille;
use BackOfficeBundle\Form\Type\BouteilleType;

use AppBundle\Entity\TypeDomaine;
use BackOfficeBundle\Form\Type\TypeDomaineType;
use AppBundle\Entity\TypeAppellation;
use BackOfficeBundle\Form\Type\TypeAppellationType;
use AppBundle\Entity\TypeDeCave;
use BackOfficeBundle\Form\Type\TypeDeCaveType;
use AppBundle\Entity\TypeContenance;
use BackOfficeBundle\Form\Type\TypeContenanceType;
use AppBundle\Entity\TypeCuvee;
use BackOfficeBundle\Form\Type\TypeCuveeType;
use AppBundle\Entity\TypeRegion;
use BackOfficeBundle\Form\Type\TypeRegionType;
use AppBundle\Entity\TypePays;
use BackOfficeBundle\Form\Type\TypePaysType;

class BouteilleController extends Controller
{    
    
    public function indexAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('AppBundle:Bouteille')->findAll();
        
        return $this->render('BackOfficeBundle:Bouteille:index.html.twig', array(
            'entities' => $entities,
            'aModerer' => false
        ));
    }
    
    public function indexModerationAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('AppBundle:Bouteille')->findBy(array('online'=>0), array('createdAt'=>'ASC'));
        
        return $this->render('BackOfficeBundle:Bouteille:index.html.twig', array(
            'entities' => $entities,
            'aModerer' => true
        ));
    }
    
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Bouteille')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        
        $noteMoyenne = $this->calculNoteMoyenne($entity);

        return $this->render('BackOfficeBundle:Bouteille:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
                    'noteMoyenne'=>$noteMoyenne,
        ));
    }
    public function newAction() {
        $entity = new Bouteille();
        $form = $this->createCreateForm($entity);

        return $this->render('BackOfficeBundle:Bouteille:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        $entity = new Bouteille();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $aModerer = false;

            if(!$entity->getOnline()){
                $aModerer = true;
            }
            
            if($aModerer){
                return $this->forward('BackOfficeBundle:Bouteille:indexModeration');
            }else{
                return $this->forward('BackOfficeBundle:Bouteille:index');
            }
        }

        return $this->render('BackOfficeBundle:Bouteille:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
        
    }
    
    private function createCreateForm(Bouteille $entity) {
        $form = $this->createForm(new BouteilleType(), $entity, array(
            'action' => $this->generateUrl('back_office_bouteille_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));
        $form->add('reset', 'reset', array('label' => 'Annuler'));

        return $form;
    }
    
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Bouteille')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackOfficeBundle:Bouteille:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }
    
    private function createEditForm(Bouteille $entity) {
        $form = $this->createForm(new BouteilleType(), $entity, array(
            'action' => $this->generateUrl('back_office_bouteille_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Bouteille')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $aModerer = false;

            if(!$entity->getOnline()){
                $aModerer = true;
            }
            
            if($aModerer){
                return $this->forward('BackOfficeBundle:Bouteille:indexModeration');
            }else{
                return $this->forward('BackOfficeBundle:Bouteille:index');
            }
        }

        return $this->render('BackOfficeBundle:Bouteille:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
        
    }
    
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        
        $aModerer = false;

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Bouteille')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }
            
            if(!$entity->getOnline()){
                $aModerer = true;
            }
            
            $this->supprimeTrocsLies($em, $entity, $id);

            $em->remove($entity);
            $em->flush();
        }

        if($aModerer){
            return $this->redirect($this->generateUrl('back_office_bouteilles_a_moderer'));
        }else{
            return $this->redirect($this->generateUrl('back_office_bouteilles'));
        }
    }
    
    
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('back_office_bouteille_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }
    
    private function supprimeTrocsLies($em, $bouteille, $id)
    {
        $allTrocs = $em->getRepository('AppBundle:Troc')->findTrocLiesToBottle($bouteille, $id);
        if($allTrocs){
            foreach($allTrocs as $troc){
                
                $trocContenu = $troc->getTrocSections()[0]->getContenu();
                
                $choixA = $trocContenu->getTrocABouteilles();
                $choixB = $trocContenu->getTrocBBouteilles();                
                foreach($choixA as $choix){
                    if($choix->getBouteille() == $bouteille){
                        $choix->setTrocContenuA(null);
                        $trocContenu->removeTrocABouteille($choix);
                        $em->remove($choix);
                    }
                }
                foreach($choixB as $choix){
                    if($choix->getBouteille() == $bouteille){
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
    
    
    private function calculNoteMoyenne($bouteille){
        
        $em = $this->getDoctrine()->getManager();
        
        $bouteilles = $em->getRepository('AppBundle:Bouteille')->findAllIdentiques($bouteille->getTypeDeVin(),$bouteille->getTypeDomaine(),$bouteille->getTypeAppellation(),$bouteille->getTypeCuvee(),$bouteille->getTypeRegion(),$bouteille->getTypePays(),$bouteille->getMillesime(),$bouteille->getTypeContenance());
        $noteTotal = 0;
        $noteMoyenne = 0;
        
        
        if($bouteilles){
            foreach($bouteilles as $bouteille){
                $noteTotal+= $bouteille->getNote();
            }
            $noteMoyenne = round($noteTotal / count($bouteilles));            
        }
        
        return $noteMoyenne;
    }
    
    
    
    public function addDomaineAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = new TypeDomaine();
        
        $form = $this->createForm(new TypeDomaineType(), $entity, array(
            'action' => $this->generateUrl('bo_bouteille_api_domaine_add'),
            'method' => 'POST',
        ));
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager(); 
            if($entity->getNameUk() == null){
                $entity->setNameUk($entity->getNameFr());
            }else if($entity->getNameFr() == null){
                $entity->setNameFr($entity->getNameUk());
            }           
            $em->persist($entity);
            $em->flush();
            
            $jsonObject = array('result'=>true, 'id'=>$entity->getId(), 'name'=>$entity->getNameFr());
            
            return new Response(json_encode($jsonObject));
        }

        return $this->render('BackOfficeBundle:Bouteille:ajouter_domaine.html.twig', array(
            'form'   => $form->createView(),
        ));
    }
    
    public function addAppellationAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = new TypeAppellation();
        
        $form = $this->createForm(new TypeAppellationType(), $entity, array(
            'action' => $this->generateUrl('bo_bouteille_api_appellation_add'),
            'method' => 'POST',
        ));
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager(); 
            if($entity->getNameUk() == null){
                $entity->setNameUk($entity->getNameFr());
            }else if($entity->getNameFr() == null){
                $entity->setNameFr($entity->getNameUk());
            }
            $em->persist($entity);
            $em->flush();
            
            $jsonObject = array('result'=>true, 'id'=>$entity->getId(), 'name'=>$entity->getNameFr());
            
            return new Response(json_encode($jsonObject));
        }

        return $this->render('BackOfficeBundle:Bouteille:ajouter_appellation.html.twig', array(
            'form'   => $form->createView(),
        ));
    }
    
    public function addCaveAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = new TypeDeCave();
        
        $form = $this->createForm(new TypeDeCaveType(), $entity, array(
            'action' => $this->generateUrl('bo_bouteille_api_cave_add'),
            'method' => 'POST',
        ));
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager(); 
            if($entity->getNameUk() == null){
                $entity->setNameUk($entity->getNameFr());
            }else if($entity->getNameFr() == null){
                $entity->setNameFr($entity->getNameUk());
            }           
            $em->persist($entity);
            $em->flush();
            
            $jsonObject = array('result'=>true, 'id'=>$entity->getId(), 'name'=>$entity->getNameFr());
            
            return new Response(json_encode($jsonObject));
        }

        return $this->render('BackOfficeBundle:Bouteille:ajouter_cave.html.twig', array(
            'form'   => $form->createView(),
        ));
    }
    
    public function addContenanceAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = new TypeContenance();
        
        $form = $this->createForm(new TypeContenanceType(), $entity, array(
            'action' => $this->generateUrl('bo_bouteille_api_contenance_add'),
            'method' => 'POST',
        ));
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager(); 
            if($entity->getNameUk() == null){
                $entity->setNameUk($entity->getNameFr());
            }else if($entity->getNameFr() == null){
                $entity->setNameFr($entity->getNameUk());
            }           
            $em->persist($entity);
            $em->flush();
            
            $jsonObject = array('result'=>true, 'id'=>$entity->getId(), 'name'=>$entity->getNameFr());
            
            return new Response(json_encode($jsonObject));
        }

        return $this->render('BackOfficeBundle:Bouteille:ajouter_contenance.html.twig', array(
            'form'   => $form->createView(),
        ));
    }
    
    public function addCuveeAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = new TypeCuvee();
        
        $form = $this->createForm(new TypeCuveeType(), $entity, array(
            'action' => $this->generateUrl('bo_bouteille_api_cuvee_add'),
            'method' => 'POST',
        ));
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager(); 
            if($entity->getNameUk() == null){
                $entity->setNameUk($entity->getNameFr());
            }else if($entity->getNameFr() == null){
                $entity->setNameFr($entity->getNameUk());
            }           
            $em->persist($entity);
            $em->flush();
            
            $jsonObject = array('result'=>true, 'id'=>$entity->getId(), 'name'=>$entity->getNameFr());
            
            return new Response(json_encode($jsonObject));
        }

        return $this->render('BackOfficeBundle:Bouteille:ajouter_cuvee.html.twig', array(
            'form'   => $form->createView(),
        ));
    }
    
    public function addRegionAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = new TypeRegion();
        
        $form = $this->createForm(new TypeRegionType(), $entity, array(
            'action' => $this->generateUrl('bo_bouteille_api_region_add'),
            'method' => 'POST',
        ));
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager(); 
            if($entity->getNameUk() == null){
                $entity->setNameUk($entity->getNameFr());
            }else if($entity->getNameFr() == null){
                $entity->setNameFr($entity->getNameUk());
            }           
            $em->persist($entity);
            $em->flush();
            
            $jsonObject = array('result'=>true, 'id'=>$entity->getId(), 'name'=>$entity->getNameFr());
            
            return new Response(json_encode($jsonObject));
        }

        return $this->render('BackOfficeBundle:Bouteille:ajouter_region.html.twig', array(
            'form'   => $form->createView(),
        ));
    }
    
    public function addPaysAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = new TypePays();
        
        $form = $this->createForm(new TypePaysType(), $entity, array(
            'action' => $this->generateUrl('bo_bouteille_api_pays_add'),
            'method' => 'POST',
        ));
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();   
            if($entity->getNameUk() == null){
                $entity->setNameUk($entity->getNameFr());
            }else if($entity->getNameFr() == null){
                $entity->setNameFr($entity->getNameUk());
            }         
            $em->persist($entity);
            $em->flush();
            
            $jsonObject = array('result'=>true, 'id'=>$entity->getId(), 'name'=>$entity->getNameFr());
            
            return new Response(json_encode($jsonObject));
        }

        return $this->render('BackOfficeBundle:Bouteille:ajouter_pays.html.twig', array(
            'form'   => $form->createView(),
        ));
    }
    
}
