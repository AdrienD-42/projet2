<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\RegisterType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\RedirectResponse;
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


    /**
     * @Route("/users/add", name="users_add")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @return RedirectResponse|Response
     */
    public function users_add(Request $request, EntityManagerInterface $em, FileUploader $fileUploader, UserPasswordEncoderInterface $encoder)
    {
        $user = new Participant();

        $form = $this->createForm(RegisterType::class, $user);
        $form->remove('id');
        $form->add('role',ChoiceType::class, [
            'label' => 'Role utilisateur',
            "mapped" => false,
            'choices'  => [
                'Utilisateur' => 'ROLE_USER',
                'Administrateur' => 'ROLE_ADMIN',
            ],
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setMotDePasse($password);
            $role = $form['role']->getData();
            $role =  array(0 => $role);
            $user->setRoles($role);

            $file = $user->geturlPhoto();
            if($file){
                $filename = $fileUploader->upload($file);
                $user->setPhoto($filename);
            }

            $em->persist($user);
            $em->flush();
            $this->addFlash('success','New user');
            return $this->redirectToRoute('home');
        }

        return $this->render('users.html.twig',[
            'users' => null,
            'page_name' => 'Ajouter un utilisateur',
            'form'=>$form->createView(),
        ]);
    }
}
