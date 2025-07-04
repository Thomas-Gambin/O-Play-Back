<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
     #[Route(path: '/login', name: 'app_login', methods: ['GET', 'POST'])]
     public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
     {
         // Obtenir l'erreur de connexion s'il y en a une
         $error = $authenticationUtils->getLastAuthenticationError();

         // Dernier nom d'utilisateur saisi par l'utilisateur
         $lastUsername = $authenticationUtils->getLastUsername();

         // Rendre le template du formulaire de connexion
         return $this->render('security/login.html.twig', [
             'last_username' => $lastUsername,
             'error' => $error,
         ]);
     }

     #[Route(path: '/logout', name: 'app_logout', methods: ['POST'])]
     public function logout(): void
     {
         throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
     }
}