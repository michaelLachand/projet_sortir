<?php

namespace App\Controller;

use App\Form\RegistrationType;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;




class SecurityController extends Controller

{
    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();
        return $this->render('main/connexion.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));

    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {

    }



}

