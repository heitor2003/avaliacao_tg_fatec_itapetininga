<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class SecurityController extends AbstractController
{
    private $logger;
    private $entityManager;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    #[Route('/login', name: 'app_login')]
    public function login(SessionInterface $session): Response
    {
        $loginError = null;
        if ($session->has('login_error')) {
            $loginError = $session->get('login_error');
            $session->remove('login_error');
        }

        return $this->render('security/login.html.twig', [
            'title' => 'Login',
            'loginError' => $loginError,
        ]);
    }

    #[Route('/login_process', name: 'app_login_process', methods: ['POST'])]
    public function loginProcess(Request $request, SessionInterface $session): Response
    {
        $email = trim($request->request->get('email'));
        $plainPassword = $request->request->get('password');

        if (empty($email) || empty($plainPassword)) {
            $session->set('login_error', "Email e senha são obrigatórios.");
            return $this->redirectToRoute('app_login');
        }

        try {
            $userRepository = $this->entityManager->getRepository(User::class);

            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user && password_verify($plainPassword, $user->getPasswordHash())) {
                $session->set('user_id', $user->getId());
                $session->set('user_name', $user->getFullName());
                $session->set('user_email', $user->getEmail());
                
                return $this->redirectToRoute('app_dashboard');
            } else {
                $session->set('login_error', "Email ou senha incorretos.");
            }
        } catch (\Exception $e) {
            $session->set('login_error', "Erro ao processar login: " . $e->getMessage());
            $this->logger->error('Login process failed: ' . $e->getMessage(), ['exception' => $e]);
        }

        return $this->redirectToRoute('app_login');
    }
}