<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CategoriesRepository $categories): Response
    {
        return $this->render('main/index.html.twig', [
            'categorieByMercerie' => $categories->findBy(['typeCategories'=>1 ]),
            'categorieByTissu' => $categories->findBy(['typeCategories' =>2])
        ]);
    }

    #[Route('/categorie/articles_mercerie/{id}', name: 'app_articles_mercerie')]
    public function articles_mercerie($id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Articles::class);
        $article = $repository->findBy(['categories'=>$id]);

        // $article = $this->getDoctrine()->getRepository(Categories::class)->findBy(''=>$articles);

        return $this->render('main/categoriemercerie.html.twig', [
            'article'=>$article,
            // dd($article)

           
        ]);
    }


}
