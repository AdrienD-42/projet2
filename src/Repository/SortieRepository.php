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

    /**
     * Methode permettant de recupérer les sorties en fonction des filtres renseignés
     * @param null $mot la valeur du champ de recherche par mot clé s'il est renseigné ou null
     * @param null $site la valeur de l'option choisie dans la liste déroulante des sites ou null
     * @param null $etat la valeur de l'option choisie dans la liste déroulante des etats de la sortie ou null
     * @param null $datedebut la date de debut de la sortie si elle est renseignée ou null
     * @param null $dateFin la date de fin de la sortie si elle est renseignée ou null
     * @param null $organisateur Sorties dont je suis l'organisateur.trice si cochée ou null
     * @param null $inscrit Sorties auxquelles je suis inscrit.e si cochée ou null
     * @param null $noInscrit Sorties auxquelles je ne suis pas inscrit.e si cochée ou null
     * @param null $passee Sorties passées si cochée ou null
     * @return \Doctrine\ORM\Query
     */
    public function findFiltres($mot = null, $site = null,$etat = null, $datedebut = null, $dateFin = null, $organisateur = null, $inscrit = null, $noInscrit = null, $passee = null) {
        //je commence par faire mes jointures entre les tables  sortie/site/organisateur/etat
        $qb = $this->createQueryBuilder('sortie')
            ->join('sortie.site', 'site') //jointure
            ->join('sortie.organisateur', 'organisateur')
            ->join('sortie.etat' , 'etat')
            ->addSelect('site') //nous selectionnons toutes les informations de site
            ->addSelect('organisateur') //nous selectionnons toutes les informations du participant organisateur
            ->addSelect('etat'); //nous selectio toutes les informations de l'etat

        //si la champs rechercher une sortie par mots cle est renseigne, je fais ma restriction et
        // je passe mon parametre
        if($mot != null){
            $qb->andWhere('sortie.nom LIKE :mot')
                ->setParameter('mot', '%'.$mot.'%');
        }

        //si l'identifiant du site (value du select) est strictement positif, je fais ma restriction et
        // je passe mon parametre
        if($site > 0){
            $qb->andWhere('site.id = :site')
                ->setParameter('site', $site);
        }

        //si l'identifiant de l'etat de la sortie (value du select) est strictemet positif, je fais ma
        //restriction et je passe mon parametre
        if($etat > 0){
            $qb->andWhere('etat.id = :etat')
                ->setParameter('etat', $etat);
        }

        //si la date de debut de la sortie n'est pas null, je fais ma restriction et je passe mon
        //parametre
        if($datedebut != null){
            $qb->andWhere('sortie.datedebut > :date_debut')
                ->setParameter('date_debut', new \DateTime($datedebut));
        }

        //si la date de fin de la sortie n'est pas null, je fais ma restriction et je passe mon
        //parametre
        if($dateFin != null){
            $qb->andWhere('sortie.debut < :date_fin')
                ->setParameter('date_fin', new \DateTime($dateFin));
        }

        //******* lorsqu'aucune "case à cocher" n'est cocher toutes les sorties s'afficher ************//
        //-- sorties dont je suis l'organisateur.trice
        if($organisateur != null){
            $organisateur = $user = $this->getEntityManager()->getRepository(Participant::class)->find($organisateur);
            $qb->andWhere('sortie.organisateur = :organisateur')
                ->setParameter('organisateur', $organisateur);
        }
        //-- sorties auxquelles je suis inscrit.e
        if($inscrit != null){
            $user = $this->getEntityManager()->getRepository(Participant::class)->find($inscrit);
            //A noter que j'ai découvert l'expression DQL MEMBER OF qui pourrait bien être intéressante dans ce cas —
            //avec la méthode d'expression idoine isMemberOf(). Quelque chose dans ce goût
            //$qb->where($qb->expr()->isMemberOf(':inscrit', $user));
            $qb->andWhere(':inscrit MEMBER OF sortie.participants')
                ->setParameter('inscrit', $user);
        }

        //-- sorties auxquelles je ne suis pas inscrit.e
        if($noInscrit != null){
            $user = $this->getEntityManager()->getRepository(Participant::class)->find($noInscrit);
            $qb->andWhere(':inscrit NOT MEMBER OF sortie.participants')
                ->setParameter('inscrit', $user);
        }

        //-- sorties passées
        if($passee != null){
            $qb->andWhere('etat.libelle = :etat')
                ->setParameter('etat', 'Passée');
        }

        $results = $qb->getQuery()->getResult();
//dd($results);
        return $results;
    }

}