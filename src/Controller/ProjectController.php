<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    #[Route('/projet/{id}', name: 'app_project_show')]
    public function show(Project $project): Response
    {
        // Ce fichier fait le lien entre l'URL et le template d'affichage
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }
}
