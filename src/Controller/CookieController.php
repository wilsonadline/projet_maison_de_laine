<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CookieController extends AbstractController
{
    #[Route('/cookie', name: 'cookie')]
    public function index(SessionInterface $session): Response
    {
        $cookie = $session;
        dd($cookie);
        return $this->render('cookie/index.html.twig', [
            'controller_name' => 'CookieController',
        ]);
    }
}
