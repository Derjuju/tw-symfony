<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DebugController extends Controller
{
    /**
     * @Route("/debug/1",name="front_debug_1")          
     */
    public function debug1Action(Request $request) {
        if (!$this->get('mail_to_user')->sendEmailConfirmation("derjuju2@yopmail.com")) {
            throw $this->createNotFoundException('Unable to send confirmation mail.');
        }
        
        return $this->render('AppBundle:Inscription:confirm.html.twig');
    }
}
