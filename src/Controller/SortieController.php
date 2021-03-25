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
     * Affichage du détail d'une Sortie
     * @Route("/sortie/{id}", name="detailSortie",
     *     requirements={"id"="\d+"}, methods={"POST","GET"})
     */
    public function detail($id, Request $request, EntityManagerInterface $emi) {
        //recuperer la fiche de la sortie dans la base de données
        $sortie = $this->getDoctrine()->getRepository(Sortie::class)->find($id);
        if($sortie == null) {
            throw $this->createNotFoundException("Sortie inconnue !");
        }

//        $rejoindres = $emi->getRepository(Rejoindre::class)->findBy(['saSortie'=>$sortie]);
//        if ($rejoindres === null) {
//            throw $this->createNotFoundException("Erreur lors de la recherche des inscriptions pour cette sortie !");
//        }

        return $this->render("sortie/detail.html.twig", [
            "sortie"=>$sortie,
            //"rejoindres"=> $rejoindres
        ]);
    }

    /**
     * @Route("/liste_sorties", name="listSorties")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function listeSortie(EntityManagerInterface $em, Request $request)
    {
// IDENTIFICATION DES PARAMETRES D'ENTREES
// FILTRER LES SORTIES PAR ETAT
        $site = $request->get('site');
        $mot = $request->get('mot');
        $dateDebut = $request->get('dateDebut');
        $dateFin = $request->get('dateFin');
        $organisateur = $request->get('checkbox_organisateur');
        $inscrit = $request->get('checkbox_inscrit');
        $noInscrit = $request->get('checkbox_noInscrit');
        $passees = $request->get('checkbox_passee');

        // RECUPERE TOUS LES SITES
        $sites = $em->getRepository(Site::class)->findAll();

        // RECUPERE TOUTES LES SORTIES FILTREES
        $sorties = $em->getRepository(Sortie::class)->findFiltres($site, $mot, $dateDebut, $dateFin,
            $organisateur, $inscrit, $noInscrit, $passees);





        return $this->render('sortie/templateListSorties.html.twig', [
            //
            'sites' => $sites,
            'sorties' => $sorties,


        ]);
    }

}
