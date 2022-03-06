<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Préfixe pour la fonction du controller Gestion des stocks
/**
* @Route("/admin", name="gestionStock_")
*/
class GestionStockController extends AbstractController
{
    // Création du chemin vers la page index Gestion des stocks
    #[Route('/gestionStock', name: 'options')]
    public function gestionStock(): Response
    {
        return $this->render('admin/gestionStock/index.html.twig');
    }
}