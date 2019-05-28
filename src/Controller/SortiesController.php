<?php

namespace App\Controller;

use App\Entity\Lieux;
use App\Entity\Sorties;
use App\Entity\Villes;
use App\Form\CreerSortieType;
use App\Form\SortiesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends Controller
{
    /**
     * @Route("/adresseLieu",name="adresseLieu")
     */
    public function getAdresseLieux(Request $request)
    {
        $idLieu = $request->get("lieu");
        dump($idLieu);

        //a revoir
        if ($idLieu == null) {
            $idLieu = 0;
        }

        $lieuxRepo = $this->getDoctrine()->getRepository(Lieux::class);
        $lieux = $lieuxRepo->findAdresseLieu($idLieu);
        dump($lieux);
        $array = [];
        foreach ($lieux as $lieu) {
            dump($lieu->getRue());
            dump($lieu->getLatitude());
            dump($lieu->getLongitude());
            $array[] = [
                'rue' => $lieu->getRue(),
                'latitude' => $lieu->getLatitude(),
                'longitude' => $lieu->getLongitude(),
            ];
        }
        return new JsonResponse($array);
    }


    /**
     * @Route("/lieuxDeVille",name="lieuxDeVille")
     */
    public function getLieux(Request $request)
    {
        $idVille = $request->get("ville");
        dump($idVille);

        $villeRepo = $this->getDoctrine()->getRepository(Villes::class);
        $ville = $villeRepo->find($idVille);
        dump($ville->getCodePostal());
        dump($ville->getNomVille());

        $lieuxRepo = $this->getDoctrine()->getRepository(Lieux::class);
        $lieux = $lieuxRepo->findLieuxByVille($idVille);
        dump($lieux);
        $array = [];
        foreach ($lieux as $lieu) {
            $array[] = [
                'id' => $lieu->getId(),
                'nom' => $lieu->getNomLieu(),
                'codePostal' => $ville->getCodePostal(),
            ];
        }
        return new JsonResponse($array);
    }

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
            $utilisateur = $this->getUser();
            $sorties = $sortieRepo->findSortieByRecherche($filtreSortie, $utilisateur);
            return $this->render('sorties/accueil.html.twig', [
                'controller_name' => 'SortiesController',
                'sorties' => $sorties,
                "filtreSortie" => $filtreSortie->createView(),
            ]);
        }
        return $this->render('sorties/accueil.html.twig', [
            'controller_name' => 'SortiesController',
            'sorties' => $sorties,
            "filtreSortie" => $filtreSortie->createView(),
        ]);
    }

    /**
     * @Route("/creerSortie", name="creer")
     */
    public function creerSortie(Request $request)
    {




        $creerSortieForm = $this->createForm(CreerSortieType::class);
        dump($creerSortieForm);
      //  $creerSortieForm->handleRequest($request);

//        if ($creerSortieForm->isSubmitted() && $creerSortieForm->isValid()) {
//            dump($creerSortieForm);
//            $sortie = new Sorties();
//        }
        return $this->render('sorties/creer.html.twig', [
            'controller_name' => 'SortiesController',
            'creerSortie' => $creerSortieForm->createView(),
        ]);
    }
}
