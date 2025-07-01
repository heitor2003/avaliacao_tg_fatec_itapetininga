<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface; // Importe SessionInterface
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface; // Para registrar erros

// Em um projeto Symfony real, você injetaria o EntityManager ou um serviço de BD para persistência.

class RegistrationController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    // Rota para exibir o formulário de cadastro
    #[Route('/cadastro', name: 'app_register')]
    public function register(SessionInterface $session): Response
    {
        // Para exibir mensagens de sucesso/erro de um processamento anterior (se houver)
        $registrationMessage = null;
        if ($session->has('registration_message')) {
            $registrationMessage = $session->get('registration_message');
            $session->remove('registration_message'); // Limpa a mensagem após exibi-la
        }
        $messageType = $session->has('registration_message_type') ? $session->get('registration_message_type') : 'info';
        $session->remove('registration_message_type');


        return $this->render('registration/register.html.twig', [
            'title' => 'Cadastro de Usuário',
            'registrationMessage' => $registrationMessage,
            'messageType' => $messageType,
        ]);
    }

    // Rota para processar o formulário de cadastro (seu register_process.php)
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
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Conexão com o banco de dados - portando diretamente sua lógica
            $host = "localhost";
            $dbname = "cta";
            $user = "postgres";
            $password_db = "postgres"; // Renomeado para evitar conflito com $password do formulário
            $db_conn = @pg_connect("host=$host dbname=$dbname user=$user password=$password_db");

            if ($db_conn) {
                pg_query($db_conn, "BEGIN");
                $query = "INSERT INTO users (email, full_name, password_hash) VALUES ($1, $2, $3)";
                $result = pg_query_params($db_conn, $query, array($email, $full_name, $password_hash));

                if ($result) {
                    pg_query($db_conn, "COMMIT");
                    $session->set('registration_message', "Cadastro realizado com sucesso! Você já pode fazer login.");
                    $session->set('registration_message_type', "success");
                } else {
                    pg_query($db_conn, "ROLLBACK");
                    $pg_error = pg_last_error($db_conn);
                    if (strpos($pg_error, 'duplicate key value violates unique constraint "users_email_key"') !== false) {
                        $session->set('registration_message', "Erro no cadastro: Este email já está em uso.");
                    } else {
                        $session->set('registration_message', "Erro no cadastro: " . $pg_error);
                    }
                    $session->set('registration_message_type', "danger");
                    $this->logger->error('Registration failed: ' . $pg_error);
                }
                pg_close($db_conn);
            } else {
                $session->set('registration_message', "Erro ao conectar ao banco de dados.");
                $session->set('registration_message_type', "danger");
                $this->logger->error('Database connection failed for registration: ' . pg_last_error());
            }
        } else {
            $session->set('registration_message', "Erros de validação:<br>" . implode("<br>", $errors));
            $session->set('registration_message_type', "danger");
        }

        return $this->redirectToRoute('app_register'); // Redireciona de volta para a página de cadastro
    }
}