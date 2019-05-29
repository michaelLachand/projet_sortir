<?php

namespace App\Repository;

use App\Entity\Sorties;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use function Sodium\add;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sorties|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sorties|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sorties[]    findAll()
 * @method Sorties[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortiesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sorties::class);
    }

    public function findSortieEtat($utilisateur)
    {
        $qb = $this->createQueryBuilder('s');
        $qb->leftJoin('s.etat', 'tt');
        $qb->leftJoin('s.participants', 'par');
        $qb->andWhere('s.site = :site')
        ->setParameter('site',$utilisateur->getSite());
        $query = $qb->getQuery();
        return $query->getResult();
    }


    public function findSortieByRecherche($recherche, $utilisateur)
    {
//        dump($recherche->getData());
        $siteId = $recherche->get("site")->getData()->getId();
        $jeSuisOrga = $recherche->get("organisateur")->getData();
        $jeSuisInscrit = $recherche->get("inscrit")->getData();
        $jeSuisNonInscrit = $recherche->get("nonInscrit")->getData();
        $sortiePassee = $recherche->get("passe")->getData();
        $nomSortie = $recherche->get("nom_sortie")->getData();
        $dateDebut = $recherche->get("entreDate")->getData();
        $dateFin = $recherche->get("etDate")->getData();
        $qb = $this->createQueryBuilder('recherche')
            ->leftJoin('recherche.etat', 'tt')
            ->leftJoin('recherche.participants', 'par');
        $qb->Join('recherche.site', 's')
            ->addSelect('s')
            ->andWhere('s.id = :id')
            ->setParameter('id', $siteId);

        if ($jeSuisOrga == true) {
            $qb->andWhere('recherche.organisateur = :orga ')
                ->setParameter('orga', $utilisateur);
        }
        if ($sortiePassee == true) {
            $qb->andWhere('recherche.etat = :etat ')
                ->setParameter('etat', 5);
        }
        if ($jeSuisInscrit == true xor $jeSuisNonInscrit) {
            if ($jeSuisInscrit == true) {
                $qb->andWhere('par = :part')
                    ->setParameter('part', $utilisateur);
            }
            if ($jeSuisNonInscrit == true) {

                //recherche de toutes les sorties ou l'utilisateur est inscrit
                $qb2 = $this->createQueryBuilder('recherche2')
                    ->innerJoin('recherche2.participants', 'par2');
                $qb2->andWhere('par2 = :part2');

                dump($qb2);

                //on enlève toutes les sorties ou il est inscrit de la requete principale
                $qb->andWhere(
                    $qb->expr()->notIn('recherche', $qb2->getDQL())
                );


                $qb->setParameter('part2', $utilisateur);
            }
        }
        if (!is_null($dateDebut) and !is_null($dateFin)) {
            $qb->andWhere('recherche.datedebut >= :debut')
                ->andWhere('recherche.datedebut <= :fin')
                ->setParameter('debut', $dateDebut)
                ->setParameter('fin', $dateFin);
        }
        if (!is_null($nomSortie)) {
            $qb->andWhere('recherche.nom_sortie LIKE :nomSortie')
                ->setParameter('nomSortie', '%' . $nomSortie . '%');
        }

        $query = $qb->getQuery();
        return $query->getResult();


    }

    // /**
    //  * @return Sorties[] Returns an array of Sorties objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sorties
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
