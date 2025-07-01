<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface; // Para registrar erros

// Lembre-se: em um projeto Symfony real, você usaria o Doctrine ORM ou um serviço injetável para a conexão com o BD.

class QualiFormController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/quali-form', name: 'app_quali_form')]
    public function index(): Response
    {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['user_id'])) {
            return $this->redirectToRoute('app_login'); // Redireciona para a rota de login
        }

        $trabalhosQuali = [];
        $errorMessage = null;

        // Lógica de conexão e consulta ao banco de dados para o dropdown
        $host = "localhost";
        $dbname = "cta";
        $user = "postgres";
        $password = "postgres";
        $db_conn = @pg_connect("host=$host dbname=$dbname user=$user password=$password");

        if ($db_conn) {
            $query = "SELECT id, titulo FROM trabalhos_quali ORDER BY titulo ASC";
            $result = pg_query($db_conn, $query);

            if ($result) {
                $trabalhosQuali = pg_fetch_all($result);
                pg_free_result($result);
            } else {
                $errorMessage = "Erro ao carregar títulos: " . pg_last_error($db_conn);
                $this->logger->error('Database query failed for quali_form: ' . pg_last_error($db_conn));
            }
            pg_close($db_conn);
        } else {
            $errorMessage = "Não foi possível conectar ao banco de dados.";
            $this->logger->error('Database connection failed for quali_form: ' . pg_last_error());
        }

        return $this->render('quali_form/index.html.twig', [
            'title' => 'Avaliação de Trabalhos de Graduação - Qualificação',
            'trabalhosQuali' => $trabalhosQuali,
            'errorMessage' => $errorMessage,
        ]);
    }
}