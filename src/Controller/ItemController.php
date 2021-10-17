<?php

namespace App\Controller;

use App\Entity\Articles;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    #[Route('/categorie/articles_mercerie/item/{id}', name: 'app_items_mercerie')]
    public function articles_mercerie($id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Articles::class);
        $article = $repository->findBy(['id'=>$id]);

        return $this->render('main/items.html.twig', [
            'article'=>$article,
        ]);
    }
}
