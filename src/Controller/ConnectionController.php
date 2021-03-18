<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnectionController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'ConnectionController',
        ]);
    }


    /*public function listeSorties() : Response
    {
          return $this->render("templateListSorties.html.twig", [
              $sorties = [
                  'nom' => ['Philo', 'Origamie', 'Perle'],
                  'date' => ['19/07/2018', '21/07/2018', '21/07/2018'],
                  'cloture' => ['19/07/2018', '21/07/2018', '21/07/2018'],
                  'inscrits/places' => ['8/8', '3/5', '2/12'],
                  'Etat' => ['En cours', 'Fermé', 'Fermé'],
                  'Inscrit' => ['X', ' ', 'X'],
                  'Organisateur' => ['Spinoz A', 'Rémi S', 'Jojo56'],
          ],]);
}*/
}

