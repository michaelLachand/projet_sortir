<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends Controller
{
    /**
     * @Route("/", name="security_login")
     */
    public function index()
    {
        return $this->render("main/connexion.html.twig");
    }
}
