<?php

namespace App\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


/**
 * Class SecurityController
 * @package App\Controller
 * @Route("/",name="security")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("",name="_root")
     */
    public function index()
    {
        if ($this->getUser()) {
            return $this->redirect($this->generateUrl("security_post_login"));
        } else {
            return $this->redirect($this->generateUrl("security_login"));
        }
    }

    /**
     * @Route("/login", name="_login")
     *
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirect($this->generateUrl("security_post_login"));
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/post_login", name="_post_login")
     */
    public function post_login()
    {
        if ($this->isGranted("ROLE_ADMIN")) {
            return $this->redirect($this->generateUrl("admin_index"));
        } elseif ($this->isGranted("ROLE_PERSONNE")) {
            return $this->redirect($this->generateUrl("personne_index"));
        } else {
            return $this->redirect($this->generateUrl("security_root"));
        }
    }


    /**
     * @Route("/logout", name="_logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
