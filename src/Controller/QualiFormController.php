<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TrabalhoQuali;
use App\Entity\PontuacaoQuali;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class QualiFormController extends AbstractController
{
    private $logger;
    private $entityManager;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager; 
    }

    #[Route('/quali-form', name: 'app_quali_form')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $trabalhosQuali = [];
        $errorMessage = null;

        try {
            $trabalhoQualiRepository = $this->entityManager->getRepository(TrabalhoQuali::class);

            $trabalhosQuali = $trabalhoQualiRepository->findBy([], ['titulo' => 'ASC']);

        } catch (\Exception $e) {
            $errorMessage = "Erro ao carregar títulos: " . $e->getMessage();
            $this->logger->error('Doctrine query failed in QualiFormController (index): ' . $e->getMessage(), ['exception' => $e]);
        }

        return $this->render('quali_form/index.html.twig', [
            'title' => 'Avaliação de Trabalhos de Graduação - Qualificação',
            'trabalhosQuali' => $trabalhosQuali,
            'errorMessage' => $errorMessage,
        ]);
    }

    #[Route('/process-quali', name: 'app_process_quali', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function processQuali(Request $request): Response
    {
        $trabalhoId = $request->request->get('title_quali');
        $tipoTrabalho = $request->request->get('tipo_trabalho');

        $capaRaw = $request->request->get('capa');
        $capaPontuacao = 0.00;
        if($capaRaw == "correto") $capaPontuacao = 0.25;
        else if($capaRaw == "incorreto") $capaPontuacao = 0.05;

        $folhaRostoRaw = $request->request->get('folha_rosto');
        $folhaRostoPontuacao = 0.00;
        if($folhaRostoRaw == "correto") $folhaRostoPontuacao = 0.25;
        else if($folhaRostoRaw == "incorreto") $folhaRostoPontuacao = 0.05;

        $sumarioRaw = $request->request->get('sumario');
        $sumarioPontuacao = 0.00;
        if($sumarioRaw == "correto") $sumarioPontuacao = 0.25;
        else if($sumarioRaw == "incorreto") $sumarioPontuacao = 0.05;

        $referenciasRaw = $request->request->get('referencias');
        $referenciasPontuacao = 0.00;
        if($referenciasRaw == "correto") $referenciasPontuacao = 0.25;
        else if($referenciasRaw == "incorreto") $referenciasPontuacao = 0.05;

        $delimitacaoTemaRaw = $request->request->get('delimitacao_tema');
        $delimitacaoTemaPontuacao = 0.00;
        if($delimitacaoTemaRaw == "bem_definido") $delimitacaoTemaPontuacao = 1.00;
        else if($delimitacaoTemaRaw == "parcialmente_definido") $delimitacaoTemaPontuacao = 0.50;

        $justificativaRaw = $request->request->get('justificativa');
        $justificativaPontuacao = 0.00;
        if($justificativaRaw == "bem_definido") $justificativaPontuacao = 1.00;
        else if($justificativaRaw == "parcialmente_definido") $justificativaPontuacao = 0.50;

        $objetivosRaw = $request->request->get('objetivos');
        $objetivosPontuacao = 0.00;
        if($objetivosRaw == "bem_definido") $objetivosPontuacao = 1.00;
        else if($objetivosRaw == "parcialmente_definido") $objetivosPontuacao = 0.50;

        $problematizacaoRaw = $request->request->get('problematizacao');
        $problematizacaoPontuacao = 0.00;
        if($problematizacaoRaw == "bem_definido") $problematizacaoPontuacao = 1.00;
        else if($problematizacaoRaw == "parcialmente_definido") $problematizacaoPontuacao = 0.50;

        $hipoteseRaw = $request->request->get('hipotese');
        $hipotesePontuacao = 0.00;
        if($hipoteseRaw == "bem_definido") $hipotesePontuacao = 1.00;
        else if($hipoteseRaw == "parcialmente_definido") $hipotesePontuacao = 0.50;

        $metodologiaRaw = $request->request->get('metodologia');
        $metodologiaPontuacao = 0.00;
        if($metodologiaRaw == "bem_definido") $metodologiaPontuacao = 1.00;
        else if($metodologiaRaw == "parcialmente_definido") $metodologiaPontuacao = 0.50;

        $revisaoBibliograficaRaw = $request->request->get('revisao_bibliografica');
        $revisaoBibliograficaPontuacao = 0.00;
        if($revisaoBibliograficaRaw == "bem_desenvolvida") $revisaoBibliograficaPontuacao = 1.00;
        else if($revisaoBibliograficaRaw == "parcialmente_desenvolvida") $revisaoBibliograficaPontuacao = 0.50;

        $aspectosQualitativosRaw = $request->request->get('aspectos_qualitativos');
        $aspectosQualitativosPontuacao = 0.00;
        if($aspectosQualitativosRaw == "correto") $aspectosQualitativosPontuacao = 1.00;
        else if($aspectosQualitativosRaw == "alguns_erros") $aspectosQualitativosPontuacao = 0.50;
        
        $totalPontuacao = ($capaPontuacao + $folhaRostoPontuacao + $sumarioPontuacao + $referenciasPontuacao +
                        $delimitacaoTemaPontuacao + $justificativaPontuacao + $objetivosPontuacao + $problematizacaoPontuacao +
                        $hipotesePontuacao + $metodologiaPontuacao + $revisaoBibliograficaPontuacao + $aspectosQualitativosPontuacao)*10/9;

        $consonanciaPlano = $request->request->get('consonancia_plano');
        $justificativaConsonancia = $request->request->get('justificativa_consonancia');
        $consideracoesFinais = $request->request->get('consideracoes_finais');

        if (empty($trabalhoId)) {
            $this->addFlash('error', 'Selecione um trabalho.');
            return $this->redirectToRoute('app_quali_form');
        }

        try {
            $trabalho = $this->entityManager->getRepository(TrabalhoQuali::class)->find($trabalhoId);
            if (!$trabalho) {
                $this->addFlash('error', 'Trabalho não encontrado.');
                return $this->redirectToRoute('app_quali_form');
            }

            $pontuacao = new PontuacaoQuali();
            $pontuacao->setTrabalho($trabalho);
            $pontuacao->setTipoTrabalho($tipoTrabalho);
            $pontuacao->setCapa($capaPontuacao);
            $pontuacao->setFolhaDeRosto($folhaRostoPontuacao);
            $pontuacao->setSumario($sumarioPontuacao);
            $pontuacao->setReferencias($referenciasPontuacao);
            $pontuacao->setDelimitacaoDoTema($delimitacaoTemaPontuacao);
            $pontuacao->setJustificativa($justificativaPontuacao);
            $pontuacao->setObjetivos($objetivosPontuacao);
            $pontuacao->setProblematizacao($problematizacaoPontuacao);
            $pontuacao->setHipotese($hipotesePontuacao);
            $pontuacao->setMetodologia($metodologiaPontuacao);
            $pontuacao->setRevisaoBibliografica($revisaoBibliograficaPontuacao);
            $pontuacao->setAspectosQualitativos($aspectosQualitativosPontuacao);
            $pontuacao->setConsonanciaPlano($consonanciaPlano);
            $pontuacao->setJustificativaConsonancia($justificativaConsonancia);
            $pontuacao->setConsideracoesFinais($consideracoesFinais);
            $pontuacao->setNotaFinal($totalPontuacao);

            $this->entityManager->persist($pontuacao);
            $this->entityManager->flush();

            $this->addFlash('success', 'Avaliação de qualificação salva com sucesso!');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erro ao salvar avaliação: ' . $e->getMessage());
            $this->logger->error('Quali form submission failed: ' . $e->getMessage(), ['exception' => $e]);
        }

        return $this->redirectToRoute('app_quali_form');
    }
}