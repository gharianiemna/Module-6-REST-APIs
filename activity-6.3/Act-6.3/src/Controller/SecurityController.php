<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;


class SecurityController extends AbstractController
{
     /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('rest_api/home.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
      /**
     * @Route(name="api_login", path="/api/login")
     * @return JsonResponse
     */
    public function api_login(): JsonResponse
    {
        $user = $this->getUser();

        return new JsonResponse([
            'user'=>json_decode($user),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
// dd($lastUsername)  ;
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
