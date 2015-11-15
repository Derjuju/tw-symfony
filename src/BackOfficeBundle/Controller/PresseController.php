<?php

namespace BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Presse;
use BackOfficeBundle\Form\Type\PresseType;
use AppBundle\Entity\Image;

class PresseController extends Controller
{
    public function indexAction() {
        
        $em = $this->getDoctrine()->getManager();
        
        $presses = $em->getRepository('AppBundle:Presse')->findBy(array(),array('actif'=>'DESC', 'createdAt'=>'ASC'));
        
        //if (!$presses) { throw $this->createNotFoundException('Unable to find entity.'); }
        
        return $this->render('BackOfficeBundle:Presse:index.html.twig', array(
            'presses' => $presses
        ));
    }
    
    public function activeAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Presse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        if($entity->getActif() == 1){
            $entity->setActif(0);
        }else{
            $entity->setActif(1);
        }
        
        $em->persist($entity);                                
        $em->flush();
        return $this->redirectToRoute('back_office_presses');
    }
    
    
    public function newAction() {
        $entity = new Presse();
        $form = $this->createCreateForm($entity);
        
        return $this->render('BackOfficeBundle:Presse:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        $entity = new Presse();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {            
            $em->persist($entity);
            $em->flush();
            
            //presse_default.png
            $dir = $this->get('kernel')->getRootDir() . "/../web/" . $this->container->getParameter('upload_presse_dir');
            if (!is_dir($dir))
            {
                mkdir($dir, 0777, true);
            }
            
            $useDefaultAvatar = true;
            if ($request->files && count($request->files) > 0)
            {
                if($request->files->get('photo_image') != null)
                {
                    $uploadedFile = $request->files->get('photo_image');
                    //$nfile = $request->request->get('file_name');
                    $file=$uploadedFile->getClientOriginalName();
                    $ext=strrchr($file,'.');
                    $nfile= date('YmdHis')."_".$entity->getId()."$ext";
                    if (file_exists($dir . $nfile))
                    {
                        unlink($dir . $nfile);
                    }
                    $uploadedFile->move($dir, $nfile);
                    $useDefaultAvatar = false;  
                }
            }
            if($useDefaultAvatar){
                $nfile= date('YmdHis')."_".$entity->getId().".png";
                copy($dir.'presse_default.png', $dir.$nfile);               
            }
            
                    
            $image = new Image();
            $image->setFile($this->container->getParameter('upload_presse_dir') . $nfile);
            $image->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
            $em->persist($image);

            $entity->setPhoto($image);
            $em->persist($entity);
                    
            $em->flush(); 
            
            return $this->redirect($this->generateUrl('back_office_presses'));
        }

        return $this->render('BackOfficeBundle:Presse:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
        
    }
    
    private function createCreateForm(Presse $entity) {
        $form = $this->createForm(new PresseType(), $entity, array(
            'action' => $this->generateUrl('back_office_presse_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));
        $form->add('reset', 'reset', array('label' => 'Annuler'));

        return $form;
    }
    
    
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Presse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('BackOfficeBundle:Presse:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }
    
    private function createEditForm(Presse $entity) {
        $form = $this->createForm(new PresseType(), $entity, array(
            'action' => $this->generateUrl('back_office_presse_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }
    
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Presse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            
            //presse_default.png
            $dir = $this->get('kernel')->getRootDir() . "/../web/" . $this->container->getParameter('upload_presse_dir');
            if (!is_dir($dir))
            {
                mkdir($dir, 0777, true);
            }
            
            if ($request->files && count($request->files) > 0)
            {
                if($request->files->get('photo_image') != null)
                {
                    $uploadedFile = $request->files->get('photo_image');
                    //$nfile = $request->request->get('file_name');
                    $file=$uploadedFile->getClientOriginalName();
                    $ext=strrchr($file,'.');
                    $nfile= date('YmdHis')."_".$entity->getId()."$ext";
                    if (file_exists($dir . $nfile))
                    {
                        unlink($dir . $nfile);
                    }
                    $uploadedFile->move($dir, $nfile);
                    if($entity->getPhoto() != null)
                    {
                        $entity->getPhoto()->setFile($this->container->getParameter('upload_presse_dir') . $nfile);
                    }else{
                        $image = new Image();
                        $image->setFile($this->container->getParameter('upload_presse_dir') . $nfile);
                        $image->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
                        $em->persist($image);

                        $entity->setPhoto($image);
                    }
                }
            }
            
            $em->persist($entity);                                
            $em->flush();
            return $this->redirectToRoute('back_office_presses');
        }

        return $this->render('BackOfficeBundle:Presse:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
        
    }
}
