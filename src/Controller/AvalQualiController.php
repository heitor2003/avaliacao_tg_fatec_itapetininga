<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\PontuacaoQuali;
use App\Entity\TrabalhoQuali;

class AvalQualiController extends AbstractController
{
    private $logger;
    private $entityManager;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    #[Route('/avaliacoes-qualificacao', name: 'app_aval_quali')]
    public function index(): Response
    {
        $avaliacoesQuali = [];
        $errorMessage = null;

        try {
            $pontuacaoQualiRepository = $this->entityManager->getRepository(PontuacaoQuali::class);

            $queryBuilder = $pontuacaoQualiRepository->createQueryBuilder('pq')
                ->select(
                    'pq.id',
                    't.titulo',
                    'pq.tipoTrabalho AS tipo_trabalho',
                    'pq.capa',
                    'pq.folhaDeRosto AS folha_de_rosto',
                    'pq.sumario',
                    'pq.referencias',
                    'pq.delimitacaoDoTema AS delimitacao_do_tema',
                    'pq.justificativa',
                    'pq.objetivos',
                    'pq.problematizacao',
                    'pq.hipotese',
                    'pq.metodologia',
                    'pq.revisaoBibliografica AS revisao_bibliografica',
                    'pq.aspectosQualitativos AS aspectos_qualitativos',
                    'pq.consonanciaPlano AS consonancia_plano',
                    'pq.justificativaConsonancia AS justificativa_consonancia',
                    'pq.consideracoesFinais AS consideracoes_finais',
                    'pq.notaFinal AS nota_final'
                )
                ->join('pq.trabalho', 't')
                ->orderBy('t.titulo', 'ASC');

            $avaliacoesQuali = $queryBuilder->getQuery()->getResult();

        } catch (\Exception $e) {
            $errorMessage = "Erro ao carregar dados do banco de dados: " . $e->getMessage();
            $this->logger->error('Doctrine query failed in AvalQualiController: ' . $e->getMessage(), ['exception' => $e]);
        }

        return $this->render('aval_quali/index.html.twig', [
            'title' => 'Avaliações de Qualificação',
            'avaliacoesQuali' => $avaliacoesQuali,
            'errorMessage' => $errorMessage,
        ]);
    }
}