<?php
// src/Controller/SocialAuthController.php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SocialAuthController extends AbstractController
{
    #[Route('/connect/google', name: 'knpu_oauth2_client_connect')]
    public function connect(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect([
                'email',
                'profile',
            ]);
    }

    #[Route('/connect/google/check', name: 'connect_google_check')]
    public function check()
    {
        return $this->redirectToRoute('app_dashboard');
    }
}
