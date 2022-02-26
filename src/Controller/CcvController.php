<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CcvController extends AbstractController
{
    #[Route('/ccv', name: 'ccv')]
    public function index(): Response
    {
        return $this->render('ccv/index.html.twig');
    }
}
