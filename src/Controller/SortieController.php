<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Site;
use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SortieController extends AbstractController
{
    private $repository;

    /**
     * @Route("/liste_sorties", name="liste_sorties")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function listeSortie(EntityManagerInterface $em, Request $request)
    {
// IDENTIFICATION DES PARAMETRES D'ENTREES
// FILTRER LES SORTIES PAR ETAT
        $idSite = $request->get('site');
        $rechercheMot = $request->get('mot');
        $dateDebut = $request->get('dateDebut');
        $dateFin = $request->get('dateFin');
        $organisateur = $request->get('checkbox_organisateur');
        $sortiesInscrit = $request->get('checkbox_inscrit');
        $sortiesNoInscrit = $request->get('checkbox_noInscrit');
        $sortiesPassees = $request->get('checkbox_passee');

        // RECUPERE TOUS LES SITES
        $sites = $em->getRepository(Site::class)->findAll();

        // RECUPERE TOUTES LES SORTIES FILTREES
        $sorties = $em->getRepository(Sortie::class)->findBycriteres($idSite, $rechercheMot, $dateDebut, $dateFin,
            $organisateur, $sortiesInscrit, $sortiesNoInscrit, $sortiesPassees);





        return $this->render('sortie/templateListSorties.html.twig', [
    //
            'sites' => $sites,
            'sorties' => $sorties,


        ]);
    }





}
