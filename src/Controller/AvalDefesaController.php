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
                    'pd.tipoTrabalho AS tipo_trabalho',
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
                    'pd.observacoes',
                    'pd.notaFinal AS nota_final'
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