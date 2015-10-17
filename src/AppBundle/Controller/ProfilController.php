<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Member;
use AppBundle\Entity\Image;
use AppBundle\Form\Type\EditMemberType;

class ProfilController extends Controller
{
    
    /**
     * @Route("/mon-profil",name="front_mon_profil")          
     */
    public function profilAction(Request $request) {
        
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) {
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $form = $this->createEditForm($user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();            
            $em->persist($user);
            $em->flush();
            
            $dir = $this->get('kernel')->getRootDir() . "/../web/" . $this->container->getParameter('upload_avatars_dir');
            if (!is_dir($dir))
            {
                mkdir($dir, 0777, true);
            }
            if ($request->files && count($request->files) > 0)
            {
                if($request->files->get('profile_picture') != null)
                {
                    $uploadedFile = $request->files->get('profile_picture');
                    //$nfile = $request->request->get('file_name');
                    $file=$uploadedFile->getClientOriginalName();
                    $ext=strrchr($file,'.');
                    $nfile= date('YmdHis')."_".$user->getLogin()."$ext";
                    if (file_exists($dir . $nfile))
                    {
                        unlink($dir . $nfile);
                    }
                    $uploadedFile->move($dir, $nfile);
                
                    // Modification de l'image lié au profil
                    if ($user->getAvatar() !== null)
                    {
                        $user->getAvatar()->setFile($this->container->getParameter('upload_avatars_dir') . $nfile);
                        $em->persist($user);
                    }
                    else
                    {
                        $image = new Image();
                        $image->setFile($this->container->getParameter('upload_avatars_dir') . $nfile);
                        $image->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
                        $em->persist($image);

                        $user->setAvatar($image);
                        $em->persist($user);
                    }
                    $em->flush();  
                }           
            }     
            
            $session = $request->getSession();
            $session->getFlashBag()->add("success","Modifications du profil enregistrées.");

            return $this->redirect($this->generateUrl('front_ma_cave'));
        }

        return $this->render('AppBundle:Cave:profil_edit.html.twig', array(
            'user'=>$user,
            'form'   => $form->createView(),
        ));
    }
    
    /**
    * Creates a form to edit a Member entity.
    *
    * @param Member $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Member $entity)
    {
        $form = $this->createForm(new EditMemberType(), $entity, array(
            'action' => $this->generateUrl('front_mon_profil'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Valider'));

        return $form;
    }
}
