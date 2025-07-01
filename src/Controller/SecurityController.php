<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; // Importe Request
use Symfony\Component\HttpFoundation\Session\SessionInterface; // Importe SessionInterface
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    // A rota para exibir o formulário de login
    #[Route('/login', name: 'app_login')]
    public function login(SessionInterface $session): Response
    {
        $loginError = null;
        if ($session->has('login_error')) {
            $loginError = $session->get('login_error');
            $session->remove('login_error'); // Limpa a mensagem de erro da sessão após exibi-la
        }

        return $this->render('security/login.html.twig', [
            'title' => 'Login', // Título para o bloco 'title' no base.html.twig
            'loginError' => $loginError, // Passa a mensagem de erro para o template
        ]);
    }

    // A rota para processar o formulário de login (seu login_process.php)
    // Normalmente, o Symfony Security lida com isso, mas para portar sua lógica, faremos manualmente.
    #[Route('/login_process', name: 'app_login_process', methods: ['POST'])]
    public function loginProcess(Request $request, SessionInterface $session): Response
    {
        // Certifique-se de que sua conexão com o banco de dados está disponível.
        // Em um projeto Symfony real, você injetaria um serviço de banco de dados ou usaria Doctrine.
        // Para este exemplo, vou incluir a lógica de conexão direta para corresponder ao seu db_con.php.
        $host = "localhost";
        $dbname = "cta";
        $user = "postgres";
        $password = "postgres";
        $db_conn = @pg_connect("host=$host dbname=$dbname user=$user password=$password");

        if (!$db_conn) {
            $session->set('login_error', "Erro ao conectar ao banco de dados.");
            return $this->redirectToRoute('app_login');
        }

        $email = trim($request->request->get('email'));
        $plainPassword = $request->request->get('password');

        if (empty($email) || empty($plainPassword)) {
            $session->set('login_error', "Email e senha são obrigatórios.");
            pg_close($db_conn);
            return $this->redirectToRoute('app_login');
        }

        $query = "SELECT id, full_name, email, password_hash FROM users WHERE email = $1";
        $result = pg_query_params($db_conn, $query, array($email));

        if ($result && pg_num_rows($result) > 0) {
            $user = pg_fetch_assoc($result);
            if (password_verify($plainPassword, $user['password_hash'])) {
                // Login bem-sucedido: Armazena informações do usuário na sessão Symfony
                // Em Symfony, você geralmente usaria o Security component para gerenciar isso,
                // mas para portar sua lógica de $_SESSION, faremos diretamente:
                $session->set('user_id', $user['id']);
                $session->set('user_name', $user['full_name']);
                $session->set('user_email', $user['email']);
                
                pg_free_result($result);
                pg_close($db_conn);

                return $this->redirectToRoute('app_dashboard'); // Redireciona para o dashboard
            } else {
                $session->set('login_error', "Email ou senha incorretos.");
            }
        } else {
            $session->set('login_error', "Email ou senha incorretos.");
        }

        pg_free_result($result);
        pg_close($db_conn);

        return $this->redirectToRoute('app_login'); // Redireciona de volta para o login em caso de falha
    }
}