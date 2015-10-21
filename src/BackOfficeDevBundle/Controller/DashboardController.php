<?php

namespace BackOfficeDevBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DashboardController extends Controller
{
    public function indexAction() {
        return $this->render('BackOfficeDevBundle:Dashboard:index.html.twig', array());
    }
}
