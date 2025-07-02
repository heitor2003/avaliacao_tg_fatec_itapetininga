<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TrabalhoDefesa;
use App\Entity\PontuacaoDefesa;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DefesaFormController extends AbstractController
{
    private $logger;
    private $entityManager;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    #[Route('/defesa-form', name: 'app_defesa_form')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $trabalhosDefesa = [];
        $errorMessage = null;

        try {
            $trabalhoDefesaRepository = $this->entityManager->getRepository(TrabalhoDefesa::class);
            $trabalhosDefesa = $trabalhoDefesaRepository->findBy([], ['titulo' => 'ASC']);
        } catch (\Exception $e) {
            $errorMessage = "Erro ao carregar títulos: " . $e->getMessage();
            $this->logger->error('Doctrine query failed in DefesaFormController (index): ' . $e->getMessage(), ['exception' => $e]);
        }

        return $this->render('defesa_form/index.html.twig', [
            'title' => 'Avaliação de Trabalhos de Graduação - Defesa',
            'trabalhosDefesa' => $trabalhosDefesa,
            'errorMessage' => $errorMessage,
        ]);
    }

    #[Route('/process-defesa', name: 'app_process_defesa', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function processDefesa(Request $request): Response
    {
        $trabalhoId = $request->request->get('title_defesa');
        $tipoTrabalho = $request->request->get('tipo_trabalho');

        $formSeguidas = (float)($request->request->get('form_seguidas') ?? 0);
        $citacoesCorretas = (float)($request->request->get('citacoes_corretas') ?? 0);
        $referenciasAdequadas = (float)($request->request->get('referencias_adequadas') ?? 0);
        $sequenciaLogica = (float)($request->request->get('sequencia_logica') ?? 0);
        $introducaoElementosBasicos = (float)($request->request->get('introducao_elementos_basicos') ?? 0);
        $resumoConteudoIntegral = (float)($request->request->get('resumo_conteudo_integral') ?? 0);
        $revisaoDesenvolvida = (float)($request->request->get('revisao_desenvolvida') ?? 0);
        $metodologiaExplicitada = (float)($request->request->get('metodologia_explicitada') ?? 0);
        $dadosPesquisaApresentados = (float)($request->request->get('dados_pesquisa_apresentados') ?? 0);
        $conclusaoCoerente = (float)($request->request->get('conclusao_coerente') ?? 0);
        $referenciasAtuais = (float)($request->request->get('referencias_atuais') ?? 0);
        $errosOrtograficos = (float)($request->request->get('erros_ortograficos') ?? 0);
        $potencialPublicacao = $request->request->get('potencial_publicacao');
        $observacoes = $request->request->get('observacoes');

        $p2ConditionalValue = ($tipoTrabalho !== "somente_revisao") ? ($dadosPesquisaApresentados * 1.5) : 0;

        $numeratorSum = ($formSeguidas * 0.5) + ($citacoesCorretas * 0.4) + $referenciasAdequadas + ($sequenciaLogica * 1.5) +
                         ($introducaoElementosBasicos * 0.5) + ($resumoConteudoIntegral * 0.5) + $revisaoDesenvolvida + $metodologiaExplicitada +
                         $p2ConditionalValue +
                         $conclusaoCoerente + ($referenciasAtuais * 0.5) + ($errosOrtograficos * 0.5);

        $numerator = $numeratorSum * 10;
        $denominator = ($tipoTrabalho !== "somente_revisao") ? 49.5 : 42;
        
        $notaFinal = ($denominator != 0) ? round($numerator / $denominator, 2) : 0;


        if (empty($trabalhoId)) {
            $this->addFlash('error', 'Selecione um trabalho.');
            return $this->redirectToRoute('app_defesa_form');
        }

        try {
            $trabalho = $this->entityManager->getRepository(TrabalhoDefesa::class)->find($trabalhoId);
            if (!$trabalho) {
                $this->addFlash('error', 'Trabalho não encontrado.');
                return $this->redirectToRoute('app_defesa_form');
            }

            $pontuacao = new PontuacaoDefesa();
            $pontuacao->setTrabalho($trabalho);
            $pontuacao->setTipoTrabalho($tipoTrabalho);
            $pontuacao->setFormSeguidas($formSeguidas);
            $pontuacao->setCitacoesCorretas($citacoesCorretas);
            $pontuacao->setReferenciasAdequadas($referenciasAdequadas);
            $pontuacao->setSequenciaLogica($sequenciaLogica);
            $pontuacao->setIntroducaoElementosBasicos($introducaoElementosBasicos);
            $pontuacao->setResumoConteudoIntegral($resumoConteudoIntegral);
            $pontuacao->setRevisaoDesenvolvida($revisaoDesenvolvida);
            $pontuacao->setMetodologiaExplicitada($metodologiaExplicitada);
            $pontuacao->setDadosPesquisaApresentados($dadosPesquisaApresentados);
            $pontuacao->setConclusaoCoerente($conclusaoCoerente);
            $pontuacao->setReferenciasAtuais($referenciasAtuais);
            $pontuacao->setErrosOrtograficos($errosOrtograficos);
            $pontuacao->setPotencialPublicacao($potencialPublicacao);
            $pontuacao->setObservacoes($observacoes);
            $pontuacao->setNotaFinal($notaFinal);

            $this->entityManager->persist($pontuacao);
            $this->entityManager->flush();

            $this->addFlash('success', 'Avaliação de defesa salva com sucesso!');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erro ao salvar avaliação: ' . $e->getMessage());
            $this->logger->error('Defesa form submission failed: ' . $e->getMessage(), ['exception' => $e]);
        }

        return $this->redirectToRoute('app_defesa_form');
    }
}