<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface; // Importa a interface do Entity Manager
use App\Entity\TrabalhoQuali; // Importa sua entidade TrabalhoQuali

class QualiFormController extends AbstractController
{
    private $logger;
    private $entityManager; // Declara a propriedade para o Entity Manager

    // Injeta LoggerInterface e EntityManagerInterface no construtor
    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager; // Atribui o Entity Manager à propriedade da classe
    }

    #[Route('/quali-form', name: 'app_quali_form')]
    public function index(): Response
    {
        // Verifica se o usuário está logado (mantendo sua lógica de sessão por enquanto)
        if (!isset($_SESSION['user_id'])) {
            return $this->redirectToRoute('app_login'); // Redireciona para a rota de login
        }

        $trabalhosQuali = [];
        $errorMessage = null;

        try {
            // PASSO 8: Interagir com o banco de dados via Doctrine ORM
            // Obtém o repositório da entidade TrabalhoQuali
            $trabalhoQualiRepository = $this->entityManager->getRepository(TrabalhoQuali::class);

            // Busca os dados (id e titulo) ordenados por titulo
            // Isso retornará um array de objetos TrabalhoQuali
            $trabalhosQuali = $trabalhoQualiRepository->findBy([], ['titulo' => 'ASC']);

            // O template Twig pode acessar propriedades de objetos diretamente (ex: row.id, row.titulo),
            // assumindo que sua entidade TrabalhoQuali possui os métodos getId() e getTitulo().

        } catch (\Exception $e) {
            // Captura qualquer exceção que possa ocorrer durante a interação com o Doctrine
            $errorMessage = "Erro ao carregar títulos: " . $e->getMessage();
            $this->logger->error('Doctrine query failed in QualiFormController: ' . $e->getMessage(), ['exception' => $e]);
        }

        return $this->render('quali_form/index.html.twig', [
            'title' => 'Avaliação de Trabalhos de Graduação - Qualificação',
            'trabalhosQuali' => $trabalhosQuali, // Passa o array de objetos TrabalhoQuali
            'errorMessage' => $errorMessage,
        ]);
    }
}