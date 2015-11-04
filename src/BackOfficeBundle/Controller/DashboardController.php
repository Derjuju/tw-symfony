<?php

namespace BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DashboardController extends Controller
{
    public function indexAction() {
        
        $em = $this->getDoctrine()->getManager();
        
        $totalTrocs = 0;
        $trocs = $em->getRepository('AppBundle:Troc')->findAllStillLive();
        if($trocs){
            $totalTrocs = count($trocs);
        }
        $totalTrocsEnded = 0;
        $trocs = $em->getRepository('AppBundle:Troc')->findAllEnded();
        if($trocs){
            $totalTrocsEnded = count($trocs);
        }
        
        return $this->render('BackOfficeBundle:Dashboard:index.html.twig', array(
            'totalTrocs' => $totalTrocs,
            'totalTrocsEnded' => $totalTrocsEnded
        ));
    }
}
