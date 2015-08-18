<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EspaceController extends Controller
{
    /**
     * @Route("/mon-espace",name="front_mon_espace")          
     */
    public function indexAction() {
        return $this->render("AppBundle:Espace:index.html.twig");
    }
}
