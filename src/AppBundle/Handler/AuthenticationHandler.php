<?php
namespace AppBundle\Handler;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;


/**
 * Description of AuthenticationHandler
 *
 */
class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    private $router;
    private $session;
    private $container;
    private $httpUtils;

 
    /**
     * Constructor
     *
     * @param     RouterInterface $router
     * @param     Session $session
     */
    public function __construct( RouterInterface $router, Session $session,\Symfony\Component\DependencyInjection\ContainerInterface $cont,\Symfony\Component\Security\Http\HttpUtils $htu )
    {
        
        $this->router  = $router;
        $this->session = $session;
        $this->container = $cont;
        $this->httpUtils=$htu;

    }
 
    /**
     * onAuthenticationSuccess
      *
     * @param     Request $request
     * @param     TokenInterface $token
     * @return     Response
     */
    public function onAuthenticationSuccess( Request $request, TokenInterface $token )
    {
        
        $curUser = $token->getUser();
        $url = $this->router->generate( 'front_accueil');
        
        if ($curUser)
        {
        
            if ($curUser->getActif()!=1 || $curUser->getEnabled()!=1)
            {
                $this->container->get('security.context')->setToken(null);
                $session = $request->getSession();
                $session->getFlashBag()->add("error","Votre compte n'est pas actif.");
                    
                if ( $request->headers->get('referer') ) {
                    $url = $request->headers->get('referer') ;
                }
            } else {
          
                $curUser->setLastConnexion(new \DateTime(date('Y-m-d H:i:s')));

                $em = $this->container->get('doctrine.orm.entity_manager');
                $em->persist($curUser);
                $em->flush();

                if ( $request->headers->get('referer') ) {
                    $url = $request->headers->get('referer') ;
                }
            }
        }
         
        return new RedirectResponse( $url );        
    }
 
    /**
     * onAuthenticationFailure
     *
     * @param     Request $request
     * @param     AuthenticationException $exception
     * @return     Response
     */
    public function onAuthenticationFailure( Request $request, AuthenticationException $exception )
    {
          
        $session = $request->getSession();
        $session->getFlashBag()->add("error","Erreur d'identification.");
        if ( $request->headers->get('referer') ) {
            $url = $request->headers->get('referer');
        } else {
            $url = $this->router->generate( 'front_accueil' );
        } 
        return new RedirectResponse( $url );
    }
    
   
}
