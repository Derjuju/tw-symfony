<?php

namespace BackOfficeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\AddressTW;
use BackOfficeBundle\Form\Type\AddressTWType;
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
    
    private function createEditForm(AddressTW $entity) {
        $form = $this->createForm(new AddressTWType(), $entity, array(
            'action' => $this->generateUrl('back_office_adresse_tw_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

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
    
    
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('back_office_adresse_tw_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }
    
    private function supprimeTrocsLies($em, $addressTW, $id)
    {
        
    }   
    
}
