<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    private $repository;

    /**
     * @Route("/sortie", name="sortie")
     * @return Response
     */
    public function Sortie(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        /** @var SortieRepository $sortieRepository */
        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sorties = $sortieRepository->findAll();
        if ($sorties != null) {
            return $this->render('sortie/templateListSorties.html.twig', [
                'sorties' => $sorties,]);
        }
    }
}

