<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface; // Importa a interface do Entity Manager
use App\Entity\PontuacaoDefesa; // Importa sua entidade PontuacaoDefesa
use App\Entity\TrabalhoDefesa; // Importa sua entidade TrabalhoDefesa (para o join)

class AvalDefesaController extends AbstractController
{
    private $logger;
    private $entityManager; // Declara a propriedade para o Entity Manager

    // Injeta LoggerInterface e EntityManagerInterface no construtor
    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager; // Atribui o Entity Manager à propriedade da classe
    }

    #[Route('/avaliacoes-defesa', name: 'app_aval_defesa')]
    public function index(): Response
    {
        // Verifica se o usuário está logado (mantendo sua lógica de sessão por enquanto)
        if (!isset($_SESSION['user_id'])) {
            return $this->redirectToRoute('app_login'); // Redireciona para a rota de login
        }

        $avaliacoesDefesa = [];
        $errorMessage = null;

        try {
            // PASSO 8: Interagir com o banco de dados via Doctrine ORM
            // Obtém o repositório da entidade PontuacaoDefesa
            $pontuacaoDefesaRepository = $this->entityManager->getRepository(PontuacaoDefesa::class);

            // Constrói uma query DQL (Doctrine Query Language) para buscar os dados
            // Usamos aliases (AS) para garantir que os nomes das chaves no array final
            // correspondam aos nomes esperados no seu template Twig (snake_case).
            $queryBuilder = $pontuacaoDefesaRepository->createQueryBuilder('pd')
                ->select(
                    'pd.id',
                    't.titulo',
                    'pd.tipoTrabalho AS tipo_trabalho', // Assumindo propriedade 'tipoTrabalho' na entidade e coluna 'tipo_trabalho' no BD
                    'pd.formSeguidas AS form_seguidas',
                    'pd.citacoesCorretas AS citacoes_corretas',
                    'pd.referenciasAdequadas AS referencias_adequadas',
                    'pd.sequenciaLogica AS sequencia_logica',
                    'pd.introducaoElementosBasicos AS introducao_elementos_basicos',
                    'pd.resumoConteudoIntegral AS resumo_conteudo_integral',
                    'pd.revisaoDesenvolvida AS revisao_desenvolvida',
                    'pd.metodologiaExplicitada AS metodologia_explicitada',
                    'pd.dadosPesquisaApresentados AS dados_pesquisa_apresentados',
                    'pd.conclusaoCoerente AS conclusao_coerente',
                    'pd.referenciasAtuais AS referencias_atuais',
                    'pd.errosOrtograficos AS erros_ortograficos',
                    'pd.potencialPublicacao AS potencial_publicacao',
                    'pd.observacoes', // Assumindo 'observacoes' já é snake_case na entidade/BD
                    'pd.notaFinal AS nota_final' // Assumindo propriedade 'notaFinal' na entidade e coluna 'nota_final' no BD
                )
                // Realiza o JOIN com a entidade TrabalhoDefesa (assumindo que há um relacionamento 'trabalho' na PontuacaoDefesa)
                ->join('pd.trabalho', 't')
                ->orderBy('t.titulo', 'ASC');

            // Executa a query e obtém os resultados como um array de arrays associativos
            $avaliacoesDefesa = $queryBuilder->getQuery()->getResult();

        } catch (\Exception $e) {
            // Captura qualquer exceção que possa ocorrer durante a interação com o Doctrine
            $errorMessage = "Erro ao carregar dados do banco de dados: " . $e->getMessage();
            $this->logger->error('Doctrine query failed in AvalDefesaController: ' . $e->getMessage(), ['exception' => $e]);
        }

        // Renderiza o template Twig, passando os dados
        return $this->render('aval_defesa/index.html.twig', [
            'title' => 'Avaliações de Defesa',
            'avaliacoesDefesa' => $avaliacoesDefesa,
            'errorMessage' => $errorMessage,
        ]);
    }
}