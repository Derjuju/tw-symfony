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
        $totalNewBottles = 0;
        $entities = $em->getRepository('AppBundle:Bouteille')->findBy(array('online'=>0), array('createdAt'=>'ASC'));
        if($trocs){
            $totalNewBottles = count($entities);
        }
        
        $totalAllTrocs = $em->getRepository('AppBundle:Troc')->countAll();
        
        $totalAllUsers = $em->getRepository('AppBundle:Member')->countAll();        
        
        $membersStats = $em->getRepository('AppBundle:Member')->getTotalByMonthYear();
        $bouteillesStats = $em->getRepository('AppBundle:Bouteille')->getTotalByMonthYear();
        
        $trocsStats = $em->getRepository('AppBundle:Troc')->getTotalByMonthYear();
        
        return $this->render('BackOfficeBundle:Dashboard:index.html.twig', array(
            'totalTrocs' => $totalTrocs,
            'totalTrocsEnded' => $totalTrocsEnded,
            'totalNewBottles' => $totalNewBottles,
            'totalAllTrocs' => $totalAllTrocs,
            'totalAllUsers' => $totalAllUsers,
            'membersStats' => $membersStats,
            'bouteillesStats' => $bouteillesStats,
            'trocsStats' => $trocsStats,
        ));
    }
}
