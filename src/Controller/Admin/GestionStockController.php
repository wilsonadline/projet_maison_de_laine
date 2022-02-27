<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/admin", name="gestionStock_")
*/
class GestionStockController extends AbstractController
{
    #[Route('/gestionStock', name: 'options')]
    public function gestionStock(): Response
    {
        return $this->render('admin/gestionStock/index.html.twig');
    }
}