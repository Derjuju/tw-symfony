<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InscriptionController extends Controller
{
    /**
     * @Route("/register",name="front_inscription")          
     */
    public function indexAction() {
        return $this->render("AppBundle:Inscription:index.html.twig");
    }
}
