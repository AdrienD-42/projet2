<?php

namespace App\Controller;

use App\Entity\Participant;

use App\Form\RegisterType;
use App\Security\UserAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param UserAuthenticator $authenticator
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator): Response
    {
        //On créer le produit
        $Participant = new Participant();
        $Participant->setActif(true);
        $Participant->setRoles(array('ROLE_USER'));
        $Participant->setAdministrateur(false);

        //on recup le formulaire
        $form = $this->createForm(RegisterType::class, $Participant);
        $form->handleRequest($request);
        //si le formulaire est soumis
        if ($form->isSubmitted() && $form->isValid()) {

            // encodage du password
            $Participant ->setPassword(
                $passwordEncoder->encodePassword(
                    $Participant ,
                    $form->get('plainPassword')->getData()
                )
            );
            //on enredistre en base de donnees
            $em = $this->getDoctrine()->getManager();
            $em ->persist($Participant);
            $em->flush();


            return $guardHandler->authenticateUserAndHandleSuccess(
                $Participant,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );

        }


        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
