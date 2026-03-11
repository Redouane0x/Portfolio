<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    // On change la route pour accepter "fr" ou "en"
    // 'defaults' permet de mettre le français par défaut si on va juste sur "/"
    #[Route('/{_locale}', name: 'app_home', requirements: ['_locale' => 'en|fr'], defaults: ['_locale' => 'fr'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }
}
