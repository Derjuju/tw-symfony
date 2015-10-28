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
        if(!$bouteille){
            throw $this->createNotFoundException('Fiche inconnue.');
        }
        
        if($bouteille->getOnline() == 0){
            throw $this->createNotFoundException('Fiche inconnue.');
        }
        
        if($bouteille->getReserved() == 1){
            throw $this->createNotFoundException('Fiche réservée.');
        }
        
        $noteMoyenne = $this->calculNoteMoyenne($bouteille);
        
        $bouteilles = $em->getRepository('AppBundle:Bouteille')->findOthersFrom($bouteille->getId(), $bouteille->getMember()->getId());

        return $this->render('AppBundle:Bouteille:fiche.html.twig', array(
            'bouteille'=>$bouteille,
            'noteMoyenne'=>$noteMoyenne,
            'bouteilles'=>$bouteilles,
        ));
    }
    
    /**
     * @Route("/bouteilles/fiche/{id}/detail",name="front_bouteille_fiche_simple")          
     */
    public function ficheSimpleAction($id) {
        
        if($id == null){$id = 0; }

        $request = $this->get('request');
        
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        // on vérifie l'existence de la bouteille et si appartient à l'utilisateur
        $bouteille = $em->getRepository('AppBundle:Bouteille')->find($id);
        if(!$bouteille){
            throw $this->createNotFoundException('Fiche inconnue.');
        }
        
        if($bouteille->getOnline() == 0){
            throw $this->createNotFoundException('Fiche inconnue.');
        }
        
        if($bouteille->getReserved() == 1){
            throw $this->createNotFoundException('Fiche réservée.');
        }

        $noteMoyenne = $this->calculNoteMoyenne($bouteille);
        
        return $this->render('AppBundle:Bouteille:fiche_simple.html.twig', array(
            'bouteille'=>$bouteille,
            'noteMoyenne'=>$noteMoyenne
        ));
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
    
}
