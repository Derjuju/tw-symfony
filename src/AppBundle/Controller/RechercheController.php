<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RechercheController extends Controller
{
    /**
     * @Route("/search/bottle",name="front_search_bouteille")          
     */
    public function searchBottleAction(Request $request) {
        return $this->render("AppBundle:Recherche:search_bottle.html.twig");
    }
    
    /**
     * @Route("/search/troqueur",name="front_search_troqueur")          
     */
    public function searchTroqueurAction(Request $request) {
        return $this->render("AppBundle:Recherche:search_troqueur.html.twig");
    }
}
