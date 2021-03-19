<?php

namespace App\Repository;


use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;



/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function findBycriteres($idSite = null, $rechercheMot = null, $dateDebut = null, $dateFin = null,
                                     $organisateur = null, $sortiesInscrit = null, $sortiesNoInscrit = null, $sortiesPassees = null)
    {

        $qd = $this->createQueryBuilder('sortie')
            ->join('sortie.site', 'site')
            ->join('sortie.organisateur', 'organisateur')
            ->join('sortie.etat', 'etat')
            ->addSelect('organisateur')
            ->addSelect('site')
            ->addSelect('etat');

        if ($idSite > 0) {
            $qd->andWhere('site.id =: idSite')
                ->setParameter('site', $idSite);

        }
        if ($rechercheMot != null) {
            $qd->andWhere('sortie.nom =: rechercheMot')
                ->setParameter('rechercheMot', '%' . $rechercheMot . '%');

        }
        if ($dateDebut != null) {
            $qd->andWhere('sortie.date_debut =: dateDebut')
                ->setParameter('dateDebut', '$dateDebut');

        }
        if ($dateFin != null) {
            $qd->andWhere('sortie.date_cloture =: dateFin')
                ->setParameter('dateFin', '$dateFin');

        }
        if ($organisateur != null) {
            $organisateur = $this->getEntityManager()->getRepository(Participant::class)->find($organisateur);
            $qd->andWhere('sortie.organisateur_id =: organisateur')
                ->setParameter('checkbox_organisateur', '$organisateur');
        }
        if ($sortiesInscrit > 0) {
            $inscrit = $this->getEntityManager()->getRepository(Participant::class)->find($sortiesInscrit);
            $qd->andWhere('sortie.organisateur_id = :inscrit')
                ->setParameter('checkbox_organisateur', '$inscrit');

        }
        if ($sortiesNoInscrit < 0) {
            $noInscrit = $this->getEntityManager()->getRepository(Participant::class)->find($sortiesNoInscrit);
            $qd->andWhere('sortie.organisateur_id =: user')
                ->setParameter('checkbox_organisateur', '$noInscrit');
        }
        if ($sortiesPassees < 0) {
            $qd->andWhere('etat.libelle = : checkbox_passee')
                ->setParameter('checkbox_passee', '$checkbox_passee');


        }


        return $qd;

    }
}
