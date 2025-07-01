<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface; // Para registrar erros

// Lembre-se: em um projeto Symfony real, você usaria o Doctrine ORM ou um serviço injetável para a conexão com o BD.

class AvalQualiController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/avaliacoes-qualificacao', name: 'app_aval_quali')]
    public function index(): Response
    {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['user_id'])) {
            return $this->redirectToRoute('app_login'); // Redireciona para a rota de login
        }

        $avaliacoesQuali = [];
        $errorMessage = null;

        // Lógica de conexão e consulta ao banco de dados
        require_once __DIR__ . '/../Utils/db_con.php';
        
        if ($db_conn) {
            $query = "SELECT
                        pq.id,
                        t.titulo,
                        pq.tipo_trabalho,
                        pq.capa,
                        pq.folha_de_rosto,
                        pq.sumario,
                        pq.referencias,
                        pq.delimitacao_do_tema,
                        pq.justificativa,
                        pq.objetivos,
                        pq.problematizacao,
                        pq.hipotese,
                        pq.metodologia,
                        pq.revisao_bibliografica,
                        pq.aspectos_qualitativos,
                        pq.consonancia_plano,
                        pq.justificativa_consonancia,
                        pq.consideracoes_finais,
                        pq.nota_final
                    FROM
                        pontuacao_quali pq
                    JOIN
                        trabalhos_quali t ON pq.id_trabalho = t.id
                    ORDER BY
                        t.titulo ASC;";

            $result = pg_query($db_conn, $query);

            if ($result) {
                if (pg_num_rows($result) > 0) {
                    $avaliacoesQuali = pg_fetch_all($result);
                }
                pg_free_result($result);
            } else {
                $errorMessage = "Erro ao carregar dados: " . pg_last_error($db_conn);
                $this->logger->error('Database query failed: ' . pg_last_error($db_conn));
            }
            pg_close($db_conn);
        } else {
            $errorMessage = "Não foi possível conectar ao banco de dados.";
            $this->logger->error('Database connection failed: ' . pg_last_error());
        }

        return $this->render('aval_quali/index.html.twig', [
            'title' => 'Avaliações de Qualificação',
            'avaliacoesQuali' => $avaliacoesQuali,
            'errorMessage' => $errorMessage,
        ]);
    }
}