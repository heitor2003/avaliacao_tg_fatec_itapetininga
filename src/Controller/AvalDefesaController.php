<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\PontuacaoDefesa;
use App\Entity\TrabalhoDefesa;

class AvalDefesaController extends AbstractController
{
    private $logger;
    private $entityManager;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    #[Route('/avaliacoes-defesa', name: 'app_aval_defesa')]
    public function index(): Response
    {
        $avaliacoesDefesa = [];
        $errorMessage = null;

        try {
            $pontuacaoDefesaRepository = $this->entityManager->getRepository(PontuacaoDefesa::class);

            $queryBuilder = $pontuacaoDefesaRepository->createQueryBuilder('pd')
                ->select(
                    'pd.id',
                    't.titulo',
                    'pd.tipo_trabalho',
                    'pd.citacoes_corretas',
                    'pd.referencias_adequadas',
                    'pd.sequencia_logica',
                    'pd.introducao_elementos_basicos',
                    'pd.resumo_conteudo_integral',
                    'pd.revisao_desenvolvida',
                    'pd.metodologia_explicitada',
                    'pd.dados_pesquisa_apresentados',
                    'pd.conclusao_coerente',
                    'pd.referencias_atuais',
                    'pd.erros_ortograficos',
                    'pd.potencial_publicacao',
                    'pd.observacoes',
                    'pd.nota_final'
                )
                ->join('pd.trabalho', 't')
                ->orderBy('t.titulo', 'ASC');

            $avaliacoesDefesa = $queryBuilder->getQuery()->getResult();

        } catch (\Exception $e) {
            $errorMessage = "Erro ao carregar dados do banco de dados: " . $e->getMessage();
            $this->logger->error('Doctrine query failed in AvalDefesaController: ' . $e->getMessage(), ['exception' => $e]);
        }

        return $this->render('aval_defesa/index.html.twig', [
            'title' => 'Avaliações de Defesa',
            'avaliacoesDefesa' => $avaliacoesDefesa,
            'errorMessage' => $errorMessage,
        ]);
    }
}