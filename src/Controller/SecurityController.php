<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\RegistrationType;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends Controller

{
    /**
     * @Route("/user", name="security_registration")
     */

    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $participants = new Participants();

        $form = $this->createForm(RegistrationType::class, $participants);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($participants, $participants->getPassword());

            $participants->setPassword($hash);

            $manager->persist($participants);
            $manager->flush();

            //return $this->redirectToRoute('security_login');
        }


        return $this->render('user/mon_profil.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/", name="security_login")
     */

    public function login()
    {
        return $this->render('main/connexion.html.twig');
    }


    /**
     * @Route("/", name="security_logout")
     */
    public function logout()
    {

    }
}

