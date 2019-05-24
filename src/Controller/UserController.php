<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/mon_profil", name="mon_profil")
     */
    public function monProfil()
    {
        $user=$this->getUser();
        return $this->render('user/mon_profil.html.twig', [
            'controller_name' => 'UserController', 'participants' => $user,
        ]);
    }

    /**
     * @Route("/afficher_profil", name="afficher_profil")
     */
    public function afficherProfil()
    {
        $user=$this->getUser();

        return $this->render('user/afficher_profil.html.twig', [
            'controller_name' => 'UserController', 'participants' => $user,
        ]);
    }


}
