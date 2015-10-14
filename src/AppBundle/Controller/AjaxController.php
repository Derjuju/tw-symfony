<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ajax controller.
 *
 */
class AjaxController extends Controller
{

    /**
     * @Route("/ajax/flashbags",name="front_ajax_flashbags")          
     */
    public function flashBagsAction()
    {
        $jsonObject = array();
        
        $session = $this->get('session');
        
        //if(count($session->getFlashBag()->get('success', array()))>0){
            $jsonObject['success'] = array();
            foreach ($session->getFlashBag()->get('success', array()) as $message) {
                $jsonObject['success'][] = $message;
            }
        //}
        //if(count($session->getFlashBag()->get('notice', array()))>0){
            $jsonObject['notice'] = array();
            foreach ($session->getFlashBag()->get('notice', array()) as $message) {
                $jsonObject['notice'][] = $message;
            }
        //}
        //if(count($session->getFlashBag()->get('warning', array()))>0){
            $jsonObject['warning'] = array();
            foreach ($session->getFlashBag()->get('warning', array()) as $message) {
                $jsonObject['warning'][] = $message;
            }
        //}
        //if(count($session->getFlashBag()->get('error', array()))>0){
            $jsonObject['error'] = array();
            foreach ($session->getFlashBag()->get('error', array()) as $message) {
                $jsonObject['error'][] = $message;
            }
        //}
        
        return new Response(json_encode($jsonObject));
        
    }
    
    /**
     * @Route("/ajax/json-response",name="front_ajax_json_redirect_response")
     * @Method({ "GET" })  
     */
    
    public function jsonRedirectResponseAction(Request $request)
    {
        $jsonObject = null;
        if(json_decode($request->get('json',null)) !== null){
            $jsonObject = $request->get('json',null);
        }
         return new Response($jsonObject);
    }
}
