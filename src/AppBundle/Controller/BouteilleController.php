<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Bouteille;

class BouteilleController extends Controller
{    
    
    /**
     * @Route("/bouteilles/fiche/{id}",name="front_bouteille_fiche")          
     */
    public function ficheAction($id) {
        
        if($id == null){$id = 0; }

        $request = $this->get('request');
        
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        // on vérifie l'existence de la bouteille et si appartient à l'utilisateur
        $bouteille = $em->getRepository('AppBundle:Bouteille')->find($id);
        if($bouteille == null){
            throw $this->createNotFoundException('Fiche inconnue.');
        }
        
        if($bouteille->getOnline() == 0){
            throw $this->createNotFoundException('Fiche inconnue.');
        }
        
        if($bouteille->getReserved() == 1){
            throw $this->createNotFoundException('Fiche réservée.');
        }
        
        
        $bouteilles = $em->getRepository('AppBundle:Bouteille')->findOthersFrom($bouteille->getId(), $bouteille->getMember()->getId());

        return $this->render('AppBundle:Bouteille:fiche.html.twig', array(
            'bouteille'=>$bouteille,
            'bouteilles'=>$bouteilles,
        ));
    }
        
}
