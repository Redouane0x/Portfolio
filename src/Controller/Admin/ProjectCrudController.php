<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
// Voici les lignes qui manquaient (les imports) :
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre du projet'),
            TextareaField::new('description')->renderAsHtml(),

            ImageField::new('image')
                ->setLabel('Image du projet')
                // Stockage physique sur le serveur (dossier public/uploads/projects)
                ->setUploadDir('public/uploads/projects/')
                // Chemin URL pour l'affichage HTML
                ->setBasePath('uploads/projects/')
                // L'image est obligatoire seulement à la création (PAGE_NEW), pas à la modification
                ->setRequired(false),

            TextField::new('url', 'Lien GitHub/Site')->hideOnIndex(), // hideOnIndex permet de ne pas encombrer la liste
        ];
    }
}
