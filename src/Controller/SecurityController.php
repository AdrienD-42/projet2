<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/profil/{id}", name="profil", requirements={"id": "\d+"})
     * @param int $id
     * @param EntityManagerInterface $em
     * @param Request $reques
     * @return Response
     */
    public function profil(EntityManagerInterface $em, Request $request) {
        $id=$request->get('id');
        $participant = $em->getRepository(Participant::class)->find($id);
        //if($participant == $this->getUser()) { return $this->redirectToRoute(" "); }
        if ($participant != null) { return $this->render('security/profils.html.twig',
            ['participant' => $participant,
                'page_name'=>'PROFIL',
            ]); }
        else { return $this->redirectToRoute("sAUTRE"); }
    }


    /**
     * @Route("/connection", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('main');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

    }

    /**
     * @Route("/deconnection", name="app_logout")
     */
    public function logout()
    {

    }


    /**
     * @Route("/newPassword/{email}", name="newPassword")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $em
     * @param $email
     * @return RedirectResponse|Response
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em, $email){
        $user = new Participant();
        $form = $this->createForm(RegisterType::class);
        $form->remove('id')
            ->remove('nom')
            ->remove('prenom')
            ->remove('telephone')
            ->remove('email')
            ->remove('site')
            ->remove('urlPhoto')
            ->remove('actif');

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $userModif = $em->getRepository(Participant::class)->find($email);

            $userModif->setPassword('');
            $password = $passwordEncoder->encodePassword($user, $form->getData()->getPassword());
            $userModif->setPassword($password);

            $em->persist($userModif);
            $em->flush();

            $this->addFlash('success','Votre mot de passe à été modifié !');

            return $this->redirectToRoute('main');
        }

        return $this->render(' ',[
            'form' => $form->createView()
        ]);
    }

        /**
     * @Route("/photo", name="photo")
     * @param Request $request
     * @return Response
     */
    public function photo(Request $request)
    {
        $form = $this->createForm(RegisterType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {   $photo ="";
            $image = $form['image']->getData();
            if ($image)
            {

                // On crée l'image dans la base de données
                $img = new Participant();
                $img->setUrlPhoto($image);
                $photo->addUrlPhoto($img);
            }
        }
        return $this->render('photo.html.twig', [
            'form' => $form->createView(),
        ]);
    }



}
