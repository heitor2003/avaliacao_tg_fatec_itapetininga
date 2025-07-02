<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Entity\User;

class RegistrationController extends AbstractController
{
    private $logger;
    private $entityManager;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    #[Route('/cadastro', name: 'app_register')]
    public function register(SessionInterface $session): Response
    {
        $registrationMessage = null;
        if ($session->has('registration_message')) {
            $registrationMessage = $session->get('registration_message');
            $session->remove('registration_message');
        }
        $messageType = $session->has('registration_message_type') ? $session->get('registration_message_type') : 'info';
        $session->remove('registration_message_type');


        return $this->render('registration/register.html.twig', [
            'title' => 'Cadastro de Usuário',
            'registrationMessage' => $registrationMessage,
            'messageType' => $messageType,
        ]);
    }

    #[Route('/register_process', name: 'app_register_process', methods: ['POST'])]
    public function registerProcess(Request $request, SessionInterface $session): Response
    {
        $email = trim($request->request->get('email'));
        $full_name = trim($request->request->get('full_name'));
        $password = $request->request->get('password');
        $confirm_password = $request->request->get('confirm_password');

        $errors = [];

        if (empty($email) || empty($full_name) || empty($password) || empty($confirm_password)) {
            $errors[] = "Todos os campos são obrigatórios.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Formato de email inválido.";
        }

        if ($password !== $confirm_password) {
            $errors[] = "A senha e a confirmação de senha não coincidem.";
        }

        if (strlen($password) < 6) {
            $errors[] = "A senha deve ter pelo menos 6 caracteres.";
        }

        if (empty($errors)) {
            $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($existingUser) {
                $errors[] = "Erro no cadastro: Este email já está em uso.";
            }
        }

        if (empty($errors)) {
            $password = password_hash($password, PASSWORD_DEFAULT);

            try {
                $user = new User();
                $user->setEmail($email);
                $user->setFullName($full_name);
                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $session->set('registration_message', "Cadastro realizado com sucesso! Você já pode fazer login.");
                $session->set('registration_message_type', "success");

            } catch (UniqueConstraintViolationException $e) {
                $session->set('registration_message', "Erro no cadastro: Este email já está em uso.");
                $session->set('registration_message_type', "danger");
                $this->logger->error('Registration failed (duplicate email): ' . $e->getMessage(), ['exception' => $e]);
            } catch (\Exception $e) {
                $session->set('registration_message', "Erro no cadastro: " . $e->getMessage());
                $session->set('registration_message_type', "danger");
                $this->logger->error('Registration failed: ' . $e->getMessage(), ['exception' => $e]);
            }
        } else {
            $session->set('registration_message', "Erros de validação:<br>" . implode("<br>", $errors));
            $session->set('registration_message_type', "danger");
        }

        return $this->redirectToRoute('app_register');
    }
}