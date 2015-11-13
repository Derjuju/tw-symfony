<?php

namespace BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Selection;
use BackOfficeBundle\Form\Type\SelectionType;
use AppBundle\Entity\Image;

class SelectionController extends Controller
{
    public function indexAction() {
        
        $em = $this->getDoctrine()->getManager();
        
        $select1 = $em->getRepository('AppBundle:Selection')->findBy(array('position'=>1));
        $select2 = $em->getRepository('AppBundle:Selection')->findBy(array('position'=>2));
        $select3 = $em->getRepository('AppBundle:Selection')->findBy(array('position'=>3));
        $select4 = $em->getRepository('AppBundle:Selection')->findBy(array('position'=>4));
        
        if (!$select1) { throw $this->createNotFoundException('Unable to find entity 1.'); }
        if (!$select2) { throw $this->createNotFoundException('Unable to find entity 2.'); }
        if (!$select3) { throw $this->createNotFoundException('Unable to find entity 3.'); }
        if (!$select4) { throw $this->createNotFoundException('Unable to find entity 4.'); }
        
        return $this->render('BackOfficeBundle:Selection:index.html.twig', array(
            'select1' => $select1[0],
            'select2' => $select2[0],
            'select3' => $select3[0],
            'select4' => $select4[0],
        ));
    }
    
    
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Selection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('BackOfficeBundle:Selection:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }
    
    private function createEditForm(Selection $entity) {
        $form = $this->createForm(new SelectionType(), $entity, array(
            'action' => $this->generateUrl('back_office_selection_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }
    
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Selection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            
            //selection_default.png
            $dir = $this->get('kernel')->getRootDir() . "/../web/" . $this->container->getParameter('upload_selections_dir');
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
                        $entity->getPhoto()->setFile($this->container->getParameter('upload_selections_dir') . $nfile);
                    }else{
                        $image = new Image();
                        $image->setFile($this->container->getParameter('upload_selections_dir') . $nfile);
                        $image->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
                        $em->persist($image);

                        $entity->setPhoto($image);
                    }
                }
            }
            
            $em->persist($entity);                                
            $em->flush();
            return $this->redirectToRoute('back_office_selections');
        }

        return $this->render('BackOfficeBundle:Selection:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
        
    }
}
