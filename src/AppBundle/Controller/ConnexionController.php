<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConnexionController extends Controller
{
    /**
     * @Route("/pbconnexion",name="front_pbconnexion")          
     */
    public function indexAction() {
        return $this->render("AppBundle:Connexion:pbconnexion.html.twig");
    }
}
