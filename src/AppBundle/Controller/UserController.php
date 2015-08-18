<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class UserController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $error="";
        if ($exception=$request->getSession()->get('_security.last_error'))
        {
          $error="Erreur lors de l'authentification.";
          $request->getSession()->remove('_security.last_error');
        }
        
        $curUser="anon.";
        $url="AppBundle::index.html.twig";
        if ($request->getSession()->get('referrer'))
          $url=$request->getSession()->get('referrer');
        
        return $this->render($url,[  'user'=>$curUser,'error'=>$error]);
    }

    

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        
    }

    /**
     * @Route("/login-check", name="login_check")
     */
    public function loginCheckAction()
    {
        
        
    }
    
    
    
    
    public function securityLoginAction()
    {
              //$curUser= $this->container->get('security.context')->getToken()->getUser();
        $request = $this->getRequest();
        return $this->render("");
    }

   
   
   
   
   

}
