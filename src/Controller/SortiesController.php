<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sorties;
use App\Form\SortiesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends Controller
{
    /**
     * @Route("/sorties", name="accueil")
     */
    public function filtreSorties(Request $request)
    {
        //je récupère toutes les sorties et je les affiche dans un tableau
        $sortieRepo = $this->getDoctrine()->getRepository(Sorties::class);
        $sorties = $sortieRepo->findSortieEtat();



        $filtreSortie = $this->createForm(SortiesType::class);
        $filtreSortie->handleRequest($request);
        if ($filtreSortie->isSubmitted() && $filtreSortie->isValid()) {
            //faire resultat du filtre
        }
        return $this->render('sorties/accueil.html.twig', [
            'controller_name' => 'SortiesController',
            'sorties'=>$sorties,
        ]);
    }
}
