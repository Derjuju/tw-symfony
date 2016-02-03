<?php

namespace BackOfficeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\AddressTW;
use AppBundle\Entity\AddressRdv;
use BackOfficeBundle\Form\Type\AddressTWType;
use BackOfficeBundle\Form\Type\AddressRdvType;
use BackOfficeBundle\Form\Type\NouvelleAddressTWType;

class AdresseTWController extends Controller
{    
    
    public function indexAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('AppBundle:AddressTW')->findAll();
        
        return $this->render('BackOfficeBundle:AddressTW:index.html.twig', array(
            'entities' => $entities
        ));
    }
    
    public function indexRdvAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('AppBundle:AddressRdv')->findAllNotChecked();
        
        return $this->render('BackOfficeBundle:AddressTW:indexRdv.html.twig', array(
            'entities' => $entities
        ));
    }
    
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:AddressTW')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackOfficeBundle:AddressTW:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
        ));
    }
    public function showRdvAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:AddressRdv')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $ignoreForm = $this->createIgnoreForm($id);

        return $this->render('BackOfficeBundle:AddressTW:showRdv.html.twig', array(
                    'entity' => $entity,
                    'ignore_form' => $ignoreForm->createView(),
        ));
    }
    public function newAction() {
        $entity = new AddressTW();
        $form = $this->createCreateForm($entity);
        
        return $this->render('BackOfficeBundle:AddressTW:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        $entity = new AddressTW();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {            
            $em->persist($entity);
            $em->flush(); 
            
            return $this->redirect($this->generateUrl('back_office_adresses_tw'));
        }

        return $this->render('BackOfficeBundle:AddressTW:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
        
    }
    
    private function createCreateForm(AddressTW $entity) {
        $form = $this->createForm(new NouvelleAddressTWType(), $entity, array(
            'action' => $this->generateUrl('back_office_adresse_tw_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));
        $form->add('reset', 'reset', array('label' => 'Annuler'));

        return $form;
    }
    
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:AddressTW')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackOfficeBundle:AddressTW:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }
    public function editRdvAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:AddressRdv')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createEditRdvForm($entity);
        $ignoreForm = $this->createIgnoreForm($id);

        return $this->render('BackOfficeBundle:AddressTW:editRdv.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'ignore_form' => $ignoreForm->createView(),
        ));
    }
    
    private function createEditForm(AddressTW $entity) {
        $form = $this->createForm(new AddressTWType(), $entity, array(
            'action' => $this->generateUrl('back_office_adresse_tw_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }
    private function createEditRdvForm(AddressRdv $entity) {
        $form = $this->createForm(new AddressRdvType(), $entity, array(
            'action' => $this->generateUrl('back_office_adresse_tw_rdv_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));
        $form->add('submit2', 'submit', array('label' => 'Ajouter aux suggestions'));

        return $form;
    }
    
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:AddressTW')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            
            $em->persist($entity);
            $em->flush();
            
            return $this->redirect($this->generateUrl('back_office_adresses_tw'));            
        }

        return $this->render('BackOfficeBundle:AddressTW:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
        
    }
    public function updateRdvAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:AddressRdv')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $ignoreForm = $this->createIgnoreForm($id);
        $editForm = $this->createEditRdvForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {            
            if($editForm->get('submit2')->isClicked()){
                $entity->setCheckedForSuggestion(1);                
                // création nouvelle AddressTW
                $addressTw = new AddressTW();
                $addressTw->setName($entity->getName());
                $addressTw->setStreet($entity->getStreet());
                $addressTw->setZipCode($entity->getZipCode());
                $addressTw->setCity($entity->getCity());
                $addressTw->setDepartement($entity->getDepartement());
                $addressTw->setRegion($entity->getRegion());
                $addressTw->setCountry($entity->getCountry());
                $addressTw->setLatitude($entity->getLatitude());
                $addressTw->setLongitude($entity->getLongitude());
                $em->persist($addressTw);
            }
            $em->persist($entity);
            $em->flush();
            
            return $this->redirect($this->generateUrl('back_office_adresses_tw_rdv'));            
        }

        return $this->render('BackOfficeBundle:AddressTW:editRdv.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'ignore_form' => $ignoreForm->createView(),
        ));
        
    }
    
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:AddressTW')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }
            
            $this->supprimeTrocsLies($em, $entity, $id);

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('back_office_adresses_tw'));        
    }
    
    public function ignoreRdvAction(Request $request, $id) {
        $form = $this->createIgnoreForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:AddressRdv')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }
            
            $entity->setCheckedForSuggestion(1);
                    
            $em->persist($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('back_office_adresses_tw_rdv'));        
    }
    
    
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('back_office_adresse_tw_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }
    
    
    private function createIgnoreForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('back_office_adresse_tw_rdv_ignore', array('id' => $id)))
                        ->setMethod('POST')
                        ->add('submit', 'submit', array('label' => 'Ignorer'))
                        ->getForm()
        ;
    }
    
    
    
    private function supprimeTrocsLies($em, $addressTW, $id)
    {
        
    }   
    
}
