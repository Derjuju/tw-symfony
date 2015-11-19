<?php

namespace BackOfficeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Member;
use BackOfficeBundle\Form\Type\MemberType;
use BackOfficeBundle\Form\Type\NouveauMemberType;

use AppBundle\Entity\Image;

class MembreController extends Controller
{    
    
    public function indexAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('AppBundle:Member')->findAll();
        
        return $this->render('BackOfficeBundle:Membre:index.html.twig', array(
            'entities' => $entities,
            'aModerer' => false
        ));
    }
    
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Member')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackOfficeBundle:Membre:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
        ));
    }
    public function newAction() {
        $entity = new Member();
        $form = $this->createCreateForm($entity);
        
        return $this->render('BackOfficeBundle:Membre:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        $entity = new Member();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {            
            $em = $this->getDoctrine()->getManager();
            
            $encoder=$this->get('security.encoder_factory')->getEncoder($entity);
            $salt_generator=$this->get('security.secure_random');
            $entity->generatePassword($encoder,$salt_generator);
            $entity->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
            $entity->setConfirmedEmail(false);
            $entity->setActif(true);
            
            $em->persist($entity);
            $em->flush();
            
            //avatar_default.jpg
            $dir = $this->get('kernel')->getRootDir() . "/../web/" . $this->container->getParameter('upload_avatars_dir');
            if (!is_dir($dir))
            {
                mkdir($dir, 0777, true);
            }
            
            $useDefaultAvatar = true;
            if ($request->files && count($request->files) > 0)
            {
                if($request->files->get('avatar_image') != null)
                {
                    $uploadedFile = $request->files->get('avatar_image');
                    //$nfile = $request->request->get('file_name');
                    $file=$uploadedFile->getClientOriginalName();
                    $ext=strrchr($file,'.');
                    $nfile= date('YmdHis')."_".$entity->getLogin()."$ext";
                    if (file_exists($dir . $nfile))
                    {
                        unlink($dir . $nfile);
                    }
                    $uploadedFile->move($dir, $nfile);
                    $useDefaultAvatar = false;  
                }
            }
            if($useDefaultAvatar){
                $nfile= date('YmdHis')."_".$entity->getLogin().".jpg";
                copy($dir.'avatar_default.jpg', $dir.$nfile);               
            }
            
                    
            $image = new Image();
            $image->setFile($this->container->getParameter('upload_avatars_dir') . $nfile);
            $image->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
            $em->persist($image);

            $entity->setAvatar($image);
            $em->persist($entity);
                    
            $em->flush();  
            
            if (!$this->get('mail_to_user')->sendEmailConfirmation($entity->getEmail(),$entity->getFirstname())) {
                throw $this->createNotFoundException('Unable to send confirmation mail.');
            }
            
            return $this->redirect($this->generateUrl('back_office_membres'));
        }

        return $this->render('BackOfficeBundle:Membre:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
        
    }
    
    private function createCreateForm(Member $entity) {
        $form = $this->createForm(new NouveauMemberType(), $entity, array(
            'action' => $this->generateUrl('back_office_membre_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));
        $form->add('reset', 'reset', array('label' => 'Annuler'));

        return $form;
    }
    
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Member')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackOfficeBundle:Membre:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }
    
    private function createEditForm(Member $entity) {
        $form = $this->createForm(new MemberType(), $entity, array(
            'action' => $this->generateUrl('back_office_membre_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Member')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            
            //avatar_default.jpg
            $dir = $this->get('kernel')->getRootDir() . "/../web/" . $this->container->getParameter('upload_avatars_dir');
            if (!is_dir($dir))
            {
                mkdir($dir, 0777, true);
            }
            
            if ($request->files && count($request->files) > 0)
            {
                if($request->files->get('avatar_image') != null)
                {
                    $uploadedFile = $request->files->get('avatar_image');
                    //$nfile = $request->request->get('file_name');
                    $file=$uploadedFile->getClientOriginalName();
                    $ext=strrchr($file,'.');
                    $nfile= date('YmdHis')."_".$entity->getId()."$ext";
                    if (file_exists($dir . $nfile))
                    {
                        unlink($dir . $nfile);
                    }
                    $uploadedFile->move($dir, $nfile);
                    if($entity->getAvatar() != null)
                    {
                        $entity->getAvatar()->setFile($this->container->getParameter('upload_avatars_dir') . $nfile);
                    }else{
                        $image = new Image();
                        $image->setFile($this->container->getParameter('upload_avatars_dir') . $nfile);
                        $image->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
                        $em->persist($image);

                        $entity->setAvatar($image);
                    }
                }
            }
            
            
            $em->persist($entity);
            $em->flush();
            
            return $this->redirect($this->generateUrl('back_office_membres'));            
        }

        return $this->render('BackOfficeBundle:Membre:edit.html.twig', array(
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
            $entity = $em->getRepository('AppBundle:Member')->find($id);

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
            return $this->redirect($this->generateUrl('back_office_membres_a_moderer'));
        }else{
            return $this->redirect($this->generateUrl('back_office_membres'));
        }
    }
    
    
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('back_office_membre_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }
    
    
    
}
