<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TrabalhoDefesa;

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
    public function index(): Response
    {
        if (!isset($_SESSION['user_id'])) {
            return $this->redirectToRoute('app_login');
        }

        $trabalhosDefesa = [];
        $errorMessage = null;

        try {
            $trabalhoDefesaRepository = $this->entityManager->getRepository(TrabalhoDefesa::class);

            $trabalhosDefesa = $trabalhoDefesaRepository->findBy([], ['titulo' => 'ASC']);

        } catch (\Exception $e) {
            $errorMessage = "Erro ao carregar títulos: " . $e->getMessage();
            $this->logger->error('Doctrine query failed in DefesaFormController: ' . $e->getMessage(), ['exception' => $e]);
        }

        return $this->render('defesa_form/index.html.twig', [
            'title' => 'Avaliação de Trabalhos de Graduação - Defesa',
            'trabalhosDefesa' => $trabalhosDefesa,
            'errorMessage' => $errorMessage,
        ]);
    }
}