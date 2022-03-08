<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    // fonction affichant l'article seulement avec son descriptif
    #[Route('/item/{id}', name: 'app_items_mercerie')]
    public function articles_mercerie($id, ArticlesRepository $articlesRepository): Response
    {
        // recup de l'article choisi grace à find by en parametre l'id
        return $this->render('main/items.html.twig', [
            'article'=>$articlesRepository->findBy(['id'=>$id])
        ]);
    }
}
