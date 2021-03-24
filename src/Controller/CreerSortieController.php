<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Form\CreerSortieType;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CreerSortieController extends AbstractController
{
    /**
     * @Route("/creationsortie", name="creationsortie")
     * @return Response
     */
    public function Creation(Request $request, EntityManagerInterface $entityManager, SiteRepository $siteRepository, ParticipantRepository $userRepository, EtatRepository $etatRepository): Response
    {
        //Création d'une instance vide de sortie
        $sortie = new Sortie();
        //Hydratation des champs du formulaire non renseignés
        $jeanPierre =  $userRepository ->getUserJeanPierre();
        $sortie -> setOrganisateur($jeanPierre);
        //$sortie -> setOrganisateur($this->getUser());
        $sortie -> setSite($jeanPierre->getSite());
        //$sortie -> setSite($this->getUser()->getSite());
        $etat = $etatRepository ->findOneBy(["libelle" => "En cours"]);
        $sortie -> setEtatSortie($etat->getId());
        //$sites = $siteRepository ->findAll();
        //Création d'une instance de CreerSortieType en passant l'instance de Sortie
        $form = $this->createForm(CreerSortieType::class, $sortie);
        //Injection des données dans le formulaire
        $form ->handleRequest($request);
        //Si le formulaire est soumi et validé
        if ($form ->isSubmitted() && $form ->isValid())
        {
            //Demande à la doctrine de sauvegarder l'instance
            $entityManager->persist($sortie);
            //J'éxecute la requête SQL
            $entityManager ->flush();
            //Ajout d'un message en session
            $this->addFlash('success', 'La sortie a bien été créee');
            //Redirection de l'utilisateur vers la page d'accueil du site
            return $this->redirectToRoute('main');
        }
        //Envoie la vue
        $formview = $form->createView();
        return $this->render('sortie/creerSortie.html.twig', array('form'=>$formview));
    }
}
