<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Bouteille;
use AppBundle\Entity\Image;
use AppBundle\Form\Type\AddBouteilleType;
use AppBundle\Form\Type\EditBouteilleType;
use AppBundle\Form\Type\ClasserCaveType;

use AppBundle\Entity\TypeDomaine;
use AppBundle\Form\Type\AddDomaineType;
use AppBundle\Entity\TypeAppellation;
use AppBundle\Form\Type\AddAppellationType;
use AppBundle\Entity\TypeCuvee;
use AppBundle\Form\Type\AddCuveeType;
use AppBundle\Entity\TypeRegion;
use AppBundle\Form\Type\AddRegionType;
use AppBundle\Entity\TypePays;
use AppBundle\Form\Type\AddPaysType;

class CaveController extends Controller
{
    /**
     * @Route("/ma-cave",name="front_ma_cave")          
     */
    public function indexAction() {
        
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) {
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $formFiltrerCave = $this->createForm(new ClasserCaveType(), null, array(
            'action' => $this->generateUrl('front_ma_cave_liste', array('filtre'=>0)),//front_ma_cave_filtrer'),
            'method' => 'POST',
        ));
        
        return $this->render("AppBundle:Cave:index.html.twig", ['user'=>$user, 'formFiltrerCave' => $formFiltrerCave->createView()]);
    }
    
    /**
     * @Route("/cave/{id}",name="front_cave")          
     */
    public function caveAction($id) {
        
        $em = $this->getDoctrine()->getManager();
        
        $troqueur = $em->getRepository('AppBundle:Member')->find($id);        
        if(!$troqueur){
            throw $this->createNotFoundException('Troqueur inconnu.');
        }
        
        $bouteilles = $em->getRepository('AppBundle:Bouteille')->findForUserOnline($id);
        
        $formFiltrerCave = $this->createForm(new ClasserCaveType(), null, array(
            'action' => $this->generateUrl('front_cave_liste', array('id'=>$id, 'filtre'=>0)),
            'method' => 'POST',
        ));
        
        return $this->render("AppBundle:Cave:cave.html.twig", ['troqueur'=>$troqueur, 'bouteilles'=>$bouteilles, 'formFiltrerCave' => $formFiltrerCave->createView()]);
    }
    
    /**
     * @Route("/ma-cave",name="front_ma_cave_filtrer")          
     */
    public function filtrerAction() {
        
    }
    
    /**
     * @Route("/ma-cave/liste/{filtre}",name="front_ma_cave_liste")          
     */
    public function listeAction($filtre) {
        if($filtre == null){$filtre = 0;}
        
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) {
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        if($filtre != 0){
            $bouteilles = $em->getRepository('AppBundle:Bouteille')->findBy(array('member' => $user, 'typeDeVin'=>$filtre));
        }else{
            $bouteilles = $em->getRepository('AppBundle:Bouteille')->findBy(array('member' => $user));
        }
        return $this->render("AppBundle:Cave:liste.html.twig", ['bouteilles'=>$bouteilles]);
    }
    
    /**
     * @Route("/cave/liste/{id}/{filtre}",name="front_cave_liste")          
     */
    public function listeCaveAction($id, $filtre) {
        if($filtre == null){$filtre = 0;}
        
        $em = $this->getDoctrine()->getManager();
        
        $troqueur = $em->getRepository('AppBundle:Member')->find($id);        
        if(!$troqueur){
            throw $this->createNotFoundException('Troqueur inconnu.');
        }
        
        if($filtre != 0){
            $bouteilles = $em->getRepository('AppBundle:Bouteille')->findBy(array('member' => $troqueur, 'typeDeVin'=>$filtre));
        }else{
            $bouteilles = $em->getRepository('AppBundle:Bouteille')->findBy(array('member' => $troqueur));
        }
        return $this->render("AppBundle:Cave:listeTroqueur.html.twig", ['bouteilles'=>$bouteilles]);
    }
    
    /**
     * @Route("/ma-cave/add",name="front_ma_cave_ajouter")          
     */
    public function ajouterAction(Request $request) {
        
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) {
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $entity = new Bouteille();
        
        $form = $this->createAddForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $entity->setMember($user);
            $entity->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
            $em->persist($entity);
            $em->flush();
            
            //bouteille_default.jpg
            $dir = $this->get('kernel')->getRootDir() . "/../web/" . $this->container->getParameter('upload_bouteilles_dir');
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
                $nfile= date('YmdHis')."_".$entity->getId().".jpg";
                copy($dir.'bouteille_default.jpg', $dir.$nfile);               
            }
            
                    
            $image = new Image();
            $image->setFile($this->container->getParameter('upload_bouteilles_dir') . $nfile);
            $image->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
            $em->persist($image);

            $entity->setPhoto($image);
            $em->persist($entity);
                    
            $em->flush(); 
            
            //$session = $request->getSession();
            //$session->getFlashBag()->add("success","Bouteille ajoutée à votre cave.");

            return $this->redirect($this->generateUrl('front_ma_cave'));
        }

        return $this->render('AppBundle:Cave:ajouter_bouteille.html.twig', array(
            'user'=>$user,
            'form'   => $form->createView(),
            'bouteille' => $entity
        ));
    }
    
    /**
    * Creates a form to add a Bouteille entity.
    *
    * @param Bouteille $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createAddForm(Bouteille $entity)
    {
        $form = $this->createForm(new AddBouteilleType(), $entity, array(
            'action' => $this->generateUrl('front_ma_cave_ajouter'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Ajouter à ma cave'));

        return $form;
    }
    
    /**
     * @Route("/ma-cave/edit/{id}",name="front_ma_cave_editer")          
     */
    public function editerAction($id) {
        
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) {
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        if($id == null){$id = 0; }

        $request = $this->get('request');
        
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        // on vérifie l'existence de la bouteille et si appartient à l'utilisateur
        $entity = $em->getRepository('AppBundle:Bouteille')->findForIdAndUser($id,$user);
        if($entity == null){
            throw $this->createNotFoundException('Edition impossible.');
        }
        $entity = $entity[0];
        
        $form = $this->createEditForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            
            //bouteille_default.jpg
            $dir = $this->get('kernel')->getRootDir() . "/../web/" . $this->container->getParameter('upload_bouteilles_dir');
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
                        $entity->getPhoto()->setFile($this->container->getParameter('upload_bouteilles_dir') . $nfile);
                    }else{
                        $image = new Image();
                        $image->setFile($this->container->getParameter('upload_bouteilles_dir') . $nfile);
                        $image->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
                        $em->persist($image);

                        $entity->setPhoto($image);
                    }
                }
            }
            
            $em->persist($entity);                    
            $em->flush(); 
            
            //$session = $request->getSession();
            //$session->getFlashBag()->add("success","Bouteille modifiée.");

            return $this->redirect($this->generateUrl('front_ma_cave'));
        }

        return $this->render('AppBundle:Cave:editer_bouteille.html.twig', array(
            'user'=>$user,
            'entity'=>$entity,
            'form'   => $form->createView(),
        ));
    }
    
    /**
    * Creates a form to edit a Bouteille entity.
    *
    * @param Bouteille $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Bouteille $entity)
    {
        $form = $this->createForm(new EditBouteilleType(), $entity, array(
            'action' => $this->generateUrl('front_ma_cave_editer', array('id'=>$entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Valider la modification'));

        return $form;
    }
    
    /**
     * @Route("/ma-cave/delete/{id}",name="front_ma_cave_supprimer")          
     */
    public function deleteAction($id) {
        
        if (! $this->get('security.context')->isGranted('ROLE_USER') ) {
            throw $this->createNotFoundException('Accès impossible.');
        }
        
        if($id == null){$id = 0; }

        $request = $this->get('request');
        
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        // on vérifie l'existence de la bouteille et si appartient à l'utilisateur
        $entity = $em->getRepository('AppBundle:Bouteille')->findForIdAndUser($id,$user);
        if($entity == null){
            throw $this->createNotFoundException('Suppression impossible.');
        }
        $entity = $entity[0];
        
        $photo = "";
        if($entity->getPhoto() != null)
        {
            $photo = $entity->getPhoto()->getFile();
        }
        $em->remove($entity);
        $em->flush(); 
        
        if($photo != ""){
            $dir = $this->get('kernel')->getRootDir() . "/../web/" . $this->container->getParameter('upload_bouteilles_dir');
            if (file_exists($dir . $photo))
            {
                unlink($dir . $photo);
            }
        }
            
        //$session = $request->getSession();
        //$session->getFlashBag()->add("success","Bouteille supprimée.");

        return $this->redirect($this->generateUrl('front_ma_cave'));
        
    }
    
    
    /**
     * @Route("/api/domaine/add",name="front_domaine_add")          
     */
    public function addDomaineAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = new TypeDomaine();
        
        $form = $this->createForm(new AddDomaineType(), $entity, array(
            'action' => $this->generateUrl('front_domaine_add'),
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

        return $this->render('AppBundle:Cave:ajouter_domaine.html.twig', array(
            'form'   => $form->createView(),
        ));
    }
    
    /**
     * @Route("/api/appellation/add",name="front_appellation_add")          
     */
    public function addAppellationAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = new TypeAppellation();
        
        $form = $this->createForm(new AddAppellationType(), $entity, array(
            'action' => $this->generateUrl('front_appellation_add'),
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

        return $this->render('AppBundle:Cave:ajouter_appellation.html.twig', array(
            'form'   => $form->createView(),
        ));
    }
    
    /**
     * @Route("/api/cuvee/add",name="front_cuvee_add")          
     */
    public function addCuveeAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = new TypeCuvee();
        
        $form = $this->createForm(new AddCuveeType(), $entity, array(
            'action' => $this->generateUrl('front_cuvee_add'),
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

        return $this->render('AppBundle:Cave:ajouter_cuvee.html.twig', array(
            'form'   => $form->createView(),
        ));
    }
    
    /**
     * @Route("/api/region/add",name="front_region_add")          
     */
    public function addRegionAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = new TypeRegion();
        
        $form = $this->createForm(new AddRegionType(), $entity, array(
            'action' => $this->generateUrl('front_region_add'),
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

        return $this->render('AppBundle:Cave:ajouter_region.html.twig', array(
            'form'   => $form->createView(),
        ));
    }
    
    /**
     * @Route("/api/pays/add",name="front_pays_add")          
     */
    public function addPaysAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = new TypePays();
        
        $form = $this->createForm(new AddPaysType(), $entity, array(
            'action' => $this->generateUrl('front_pays_add'),
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

        return $this->render('AppBundle:Cave:ajouter_pays.html.twig', array(
            'form'   => $form->createView(),
        ));
    }
}
