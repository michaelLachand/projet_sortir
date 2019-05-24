<?php

namespace App\Controller;

use App\Form\RegistrationType;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;




class SecurityController extends Controller

{
    /**
     * @Route("/user", name="security_registration")
     */

    public function registration(Request $request)
    {

        $user = $this->getUser();
        if ($user->getPhoto() !=""){
            $user->setPhoto(
                new File($this->getParameter('images_directory').'/'.$user->getPhoto())
            );
        }
         $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $participantsId = $user;
            //$password = $encoder->encodePassword($participantsId, $participantsId->getPassword());
            $participantsId->setAdministrateur(false);
            $participantsId->setActif(true);
            //$participantsId->setPassword($password);
            $file = $participantsId->getPhoto();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

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
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();
        return $this->render('main/connexion.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));

    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {

    }

    private function generateUniqueFileName()
    {
             return md5(uniqid());
    }


}

