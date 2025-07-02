<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TrabalhoQuali;

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
    public function index(): Response
    {
        if (!isset($_SESSION['user_id'])) {
            return $this->redirectToRoute('app_login');
        }

        $trabalhosQuali = [];
        $errorMessage = null;

        try {
            $trabalhoQualiRepository = $this->entityManager->getRepository(TrabalhoQuali::class);

            $trabalhosQuali = $trabalhoQualiRepository->findBy([], ['titulo' => 'ASC']);

        } catch (\Exception $e) {
            $errorMessage = "Erro ao carregar títulos: " . $e->getMessage();
            $this->logger->error('Doctrine query failed in QualiFormController: ' . $e->getMessage(), ['exception' => $e]);
        }

        return $this->render('quali_form/index.html.twig', [
            'title' => 'Avaliação de Trabalhos de Graduação - Qualificação',
            'trabalhosQuali' => $trabalhosQuali,
            'errorMessage' => $errorMessage,
        ]);
    }
}