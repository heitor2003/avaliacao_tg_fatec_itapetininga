<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface; // Para registrar erros

// Certifique-se de que sua conexão com o banco de dados está disponível.
// Em um projeto Symfony real, você usaria o Doctrine ORM ou um serviço de banco de dados personalizado injetável.
// Para este exemplo, vou incluir a lógica de conexão direta para corresponder ao seu db_con.php.
// MAS, para um projeto Symfony bem estruturado, você DEVE criar um serviço para isso.

class AvalDefesaController extends AbstractController
{
    private $logger; // Variável para o logger

    // Injeção de dependência para o logger
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/avaliacoes-defesa', name: 'app_aval_defesa')]
    public function index(): Response
    {
        // Verifica se o usuário está logado
        // Em um projeto Symfony com Security Bundle, você usaria isGranted('ROLE_USER') ou anotações como #[IsGranted('ROLE_USER')]
        // Para portar diretamente sua lógica de sessão:
        if (!isset($_SESSION['user_id'])) {
             // Redireciona para a rota de login
            return $this->redirectToRoute('app_login'); // Assumindo que você tem uma rota nomeada 'app_login'
        }

        $avaliacoesDefesa = [];
        $errorMessage = null;

        // Lógica de conexão e consulta ao banco de dados (portada do seu db_con.php e do template)
        require_once __DIR__ . '/../Utils/db_con.php';

        if ($db_conn) {
            $query = "SELECT
                        pd.id,
                        t.titulo,
                        pd.tipo_trabalho,
                        pd.form_seguidas,
                        pd.citacoes_corretas,
                        pd.referencias_adequadas,
                        pd.sequencia_logica,
                        pd.introducao_elementos_basicos,
                        pd.resumo_conteudo_integral,
                        pd.revisao_desenvolvida,
                        pd.metodologia_explicitada,
                        pd.dados_pesquisa_apresentados,
                        pd.conclusao_coerente,
                        pd.referencias_atuais,
                        pd.erros_ortograficos,
                        pd.potencial_publicacao,
                        pd.observacoes,
                        pd.nota_final
                    FROM
                        pontuacao_defesa pd
                    JOIN
                        trabalhos_defesa t ON pd.id_trabalho = t.id
                    ORDER BY
                        t.titulo ASC;";

            $result = pg_query($db_conn, $query);

            if ($result) {
                if (pg_num_rows($result) > 0) {
                    $avaliacoesDefesa = pg_fetch_all($result);
                }
                pg_free_result($result);
            } else {
                $errorMessage = "Erro ao carregar dados: " . pg_last_error($db_conn);
                $this->logger->error('Database query failed: ' . pg_last_error($db_conn)); // Registra o erro
            }
            pg_close($db_conn);
        } else {
            $errorMessage = "Não foi possível conectar ao banco de dados.";
            $this->logger->error('Database connection failed: ' . pg_last_error()); // Registra o erro
        }

        // Renderiza o template Twig, passando os dados
        return $this->render('aval_defesa/index.html.twig', [
            'title' => 'Avaliações de Defesa', // Título para o bloco 'title' no base.html.twig
            'avaliacoesDefesa' => $avaliacoesDefesa, // Dados da consulta
            'errorMessage' => $errorMessage, // Mensagem de erro
        ]);
    }
}