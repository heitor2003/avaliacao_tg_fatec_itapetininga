<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $user = $this->getUser(); 

        $userName = $user ? ($user->getFullName() ?? $user->getUserIdentifier()) : 'Visitante'; 

        return $this->render('dashboard/index.html.twig', [
            'title' => 'Dashboard - Avaliação de Trabalhos de Graduação',
            'userName' => $userName,
        ]);
    }
}