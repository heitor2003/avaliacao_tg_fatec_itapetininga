<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TrabalhoDefesa;
use App\Entity\TrabalhoQuali;

class WorkInsertionController extends AbstractController
{
    private $logger;
    private $entityManager;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    #[Route('/inserir-trabalho', name: 'app_insert_work')]
    public function insertForm(): Response
    {
        return $this->render('work_insertion/form.html.twig', [
            'title' => 'Inserir Novo Trabalho',
        ]);
    }

    #[Route('/processar-insercao-trabalho', name: 'app_process_insert_work', methods: ['POST'])]
    public function processInsertion(Request $request): Response
    {
        $titulo = trim($request->request->get('titulo'));
        $tipoTrabalho = $request->request->get('tipo_trabalho');

        if (empty($titulo) || empty($tipoTrabalho)) {
            $this->addFlash('error', 'Título e tipo de trabalho são obrigatórios.');
            return $this->redirectToRoute('app_insert_work');
        }

        try {
            if ($tipoTrabalho === 'quali') {
                $trabalho = new TrabalhoQuali();
                $trabalho->setTitulo($titulo);
            } elseif ($tipoTrabalho === 'defesa') {
                $trabalho = new TrabalhoDefesa();
                $trabalho->setTitulo($titulo);
            } else {
                $this->addFlash('error', 'Tipo de trabalho inválido.');
                return $this->redirectToRoute('app_insert_work');
            }

            $this->entityManager->persist($trabalho);
            $this->entityManager->flush();

            $this->addFlash('success', 'Trabalho "' . $titulo . '" inserido com sucesso na tabela de ' . $tipoTrabalho . '.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erro ao inserir o trabalho: ' . $e->getMessage());
            $this->logger->error('Work insertion failed: ' . $e->getMessage(), ['exception' => $e]);
        }

        return $this->redirectToRoute('app_insert_work');
    }
}