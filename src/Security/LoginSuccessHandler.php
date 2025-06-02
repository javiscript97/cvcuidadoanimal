<?php
// src/Security/LoginSuccessHandler.php
namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    { 
         
        $user = $token->getUser();
        

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return new RedirectResponse($this->router->generate('app_panel'));
        } elseif (in_array('ROLE_VET', $user->getRoles())) {
            return new RedirectResponse($this->router->generate('app_home'));
        } else {
            return new RedirectResponse($this->router->generate('app_home'));
        }

        return new RedirectResponse($this->router->generate('app_home'));
    }
}
