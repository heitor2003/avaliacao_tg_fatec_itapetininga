<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface; // Importe SessionInterface
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    // A rota para exibir o dashboard
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(SessionInterface $session): Response
    {
        // Verifica se o usuário está logado
        // Para portar diretamente sua lógica de $_SESSION, mas em Symfony, use o Security Component.
        if (!$session->has('user_id')) {
            $session->set('login_error', "Você precisa estar logado para acessar esta página.");
            return $this->redirectToRoute('app_login'); // Redireciona para a rota de login
        }

        $userName = $session->get('user_name'); // Pega o nome do usuário da sessão

        return $this->render('dashboard/index.html.twig', [
            'title' => 'Dashboard - Avaliação de Trabalhos de Graduação',
            'userName' => $userName, // Passa o nome do usuário para o template
        ]);
    }
}