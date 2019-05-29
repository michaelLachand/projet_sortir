<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieux;
use App\Entity\Sorties;
use App\Entity\Villes;
use App\Form\CreerSortieType;
use App\Form\SortiesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends Controller
{
    /**
     * @Route("annuler",name="annuler")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function annuler(Request $request, EntityManagerInterface $em){
        $idSortie = $request->get('id');
        $sortieRepo = $this->getDoctrine()->getRepository(Sorties::class);
        $sortie = $sortieRepo->find($idSortie);
        $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
        $etatAnnulee = $etatRepo->find(6);
        $sortie->setEtat($etatAnnulee);
        $em->persist($sortie);
        $em->flush();
        $this->addFlash('success', 'Votre sortie a bien été annulée');
        return $this->redirectToRoute("accueil");
    }

    /**
     * @Route("seDesister",name="seDesister")
     * @param Request $request
     * @param EntityManagerInterface $em
     */
    public function seDesister(Request $request, EntityManagerInterface $em)
    {
        $utilisateur = $this->getUser();
        $idSortie = $request->get('id');
        $sortieRepo = $this->getDoctrine()->getRepository(Sorties::class);
        $sortie = $sortieRepo->find($idSortie);
        $sortie->removeParticipant($utilisateur);
        $em->persist($sortie);
        $em->flush();
        $this->addFlash('success', 'Vous êtes bien désinscrit de la sortie');
        return $this->redirectToRoute("accueil");
    }

    /**
     * @Route("/sInscrire",name="sInscrire")
     */
    public function sInscrire(Request $request, EntityManagerInterface $em)
    {
        $utilisateur = $this->getUser();
        $idSortie = $request->get('id');
        $sortieRepo = $this->getDoctrine()->getRepository(Sorties::class);
        $sortie = $sortieRepo->find($idSortie);
        $sortie->addParticipant($utilisateur);
        $em->persist($sortie);
        $em->flush();
        $this->addFlash('success', 'Vous êtes bien inscrit à la sortie');

        return $this->redirectToRoute("accueil");
    }


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
        $utilisateur = $this->getUser();

        //je récupère toutes les sorties et je les affiche dans un tableau
        $sortieRepo = $this->getDoctrine()->getRepository(Sorties::class);
        $sorties = $sortieRepo->findSortieEtat($utilisateur);

        $filtreSortie = $this->createForm(SortiesType::class);
        $filtreSortie->handleRequest($request);

        if ($filtreSortie->isSubmitted() && $filtreSortie->isValid()) {

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
    public function creerSortie(Request $request, EntityManagerInterface $em)
    {
        $sortie = new Sorties();
        $creerSortieForm = $this->createForm(CreerSortieType::class, $sortie);
        $creerSortieForm->handleRequest($request);

        if ($creerSortieForm->isSubmitted() && $creerSortieForm->isValid()) {
            $utilisateur = $this->getUser();
            $sortie->setOrganisateur($utilisateur);
            $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
            $etatOuverte = $etatRepo->find(2);
//            dump($etatOuverte);
            $sortie->setEtat($etatOuverte);
            $sortie->addParticipant($utilisateur);
            $sortie->setSite($utilisateur->getSite());
//            dump($creerSortieForm);
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'La sortie a bien été créée');

            return $this->redirectToRoute("accueil");
        }
        return $this->render('sorties/creer.html.twig', [
            'controller_name' => 'SortiesController',
            'creerSortie' => $creerSortieForm->createView(),
        ]);
    }
}
