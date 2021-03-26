<?php

namespace App\Controller;

use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnulerSortieController extends AbstractController
{
    /**
     * @Route("/annulersortie", name="annulersortie")
     */
    public function Annuler(Request $request, SortieRepository $sortieRepository, EtatRepository $etatRepository, EntityManagerInterface $entityManager): Response
    {
        //Récupérer l'identifiant de la sortie
        $sortieId = $request ->query->get('sortieId');
        //Récupérer la sortie
        $sortie = $sortieRepository->find($sortieId);

        //Si l'id de l'utilisateur correspond à l'organisateur de la sortie
        if ($sortie ->getOrganisateur()->getId() == $this->getUser()->getId())
        {
            //$etat = $etatRepository->find(2);
            //$sortie ->setEtatSortie($etat);
            $entityManager->remove($sortie);
            $entityManager->flush();

        } else
        {
            $commentaire = "Vous ne disposez pas des droits pour annuler cette sortie";
        }

        return $this->render('sortie/annulerSortie.html.twig', array('commentaire' => $commentaire ));

    }


}
