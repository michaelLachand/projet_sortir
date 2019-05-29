<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\ChangePasswordType;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/mon_profil", name="mon_profil")
     */
    public function monProfil(Request $request)
    {
        /** @var Participants $user */
        $user = $this->getUser();
        $user->setPhotoUrl($user->getPhoto());
        if ($user->getPhoto() !=""){
            $user->setPhoto(
                new File($this->getParameter('images_directory') . '/' . $user->getPhoto())
            );
        }
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $participantsId = $user;
            //$password = $encoder->encodePassword($participantsId, $participantsId->getPassword());
            $participantsId->setAdministrateur(false);
            $participantsId->setActif(true);
            //$participantsId->setPassword($password);
            $file = $participantsId->getPhoto();
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $participantsId->setPhoto($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($participantsId);
            $em->flush();

            $this->addFlash("success", "Les modifications ont été prises en compte");
            return $this->redirectToRoute('afficher_profil');
        }


        return $this->render('user/mon_profil.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/afficher_profil", name="afficher_profil")
     */
    public function afficherProfil(Request $request)
    {
        $idParticipant = $request->get('id');
        $participantRepo = $this->getDoctrine()->getRepository(Participants::class);
        $participant = $participantRepo->find($idParticipant);
        return $this->render('user/afficher_profil.html.twig', [
            'controller_name' => 'UserController', 'participant' => $participant,
        ]);
    }

    /**
     * @Route("/mot_de_passe", name="mot_de_passe")
     */
    public function motDePasse(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $formPassword = $this->createForm(ChangePasswordType::class);

        $formPassword->handleRequest($request);
        ;

        if ($formPassword->isSubmitted() && $formPassword->isValid()) {

            $passwordEncoder = $this->get('security.password_encoder');
            $oldPassword = $request->request->get('change_password')['oldPassword'];
                // Si l'ancien mot de passe est bon
            if ($passwordEncoder->isPasswordValid($user, $oldPassword)) {

                //$newEncodedPassword = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($passwordEncoder->encodePassword($user,$formPassword->get('password')->getData()));

                $em->persist($user);
                $em->flush();

                $this->addFlash('notice', 'Votre mot de passe à bien été changé !');

                return $this->redirectToRoute('accueil');


            } else {
                $formPassword->addError(new FormError('Ancien mot de passe incorrect'));
            }
        }

        return $this->render('user/mot_de_passe.html.twig', ['formPassword' => $formPassword->createView()]
        );
    }

    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

}

