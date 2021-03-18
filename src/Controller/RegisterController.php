<?php

namespace App\Controller;

use App\Entity\Participant;

use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request): Response
    {
        //On créer le produit
        $Participant = new Participant();
        $Participant->setActif(true);
        $Participant->setRoles(array('ROLE_USER'));
        $Participant->setAdministrateur(false);

        //on recup le formulaire
        $form = $this->createForm(RegisterType::class , $Participant);
        $form->handleRequest($request);
        //si le formulaire est soumis
        if ($form->isSubmitted()){

            $em = $this->getDoctrine()->getManager();
            $em ->persist($Participant);
            $em->flush();
            return new Response('Vous etes inscript');

        }


        //on genere le HTML du formulaire créer
        $formView = $form->createView();

        return $this->render('registration/register.html.twig',
            array('form'=>$formView));


    }
}
