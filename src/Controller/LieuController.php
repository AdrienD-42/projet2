<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
//    /**
//     * @Route("/lieu", name="lieu")
//     */
//    public function index(): Response
//    {
//        return $this->render('lieu/index.html.twig', [
//            'controller_name' => 'LieuController',
//        ]);
//    }


    /**
     * @Route("/ajout_lieu" , name="ajoutlieu")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return RedirectResponse|Response
     */
    public function add(Request $request, EntityManagerInterface $em){
        $lieu = new Lieu();
        $form = $this->createForm(LieuType::class, $lieu);
        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lieu = $form->getData();
            $em->persist($lieu);
            $em->flush();
            $this->addFlash('success', 'Le lieu a été ajouté !');
            return $this->redirectToRoute('lieu');
        }

        return $this->render('ajoutLieu.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/supp_lieu/{id}", name="supp_lieu" , requirements={"id"="\d+"})
     * @param Lieu $lieu
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function delete(Lieu $lieu, Request $request, EntityManagerInterface $em)
    {
        $lieu = $em->getRepository(Lieu::class)->find($request->get('id'));

        $em->remove($lieu);
        $em->flush();
        $this->addFlash('success', 'Le lieu a été supprimé !');

        return $this->redirectToRoute('lieu');
    }


}


