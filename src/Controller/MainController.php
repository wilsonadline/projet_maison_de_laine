<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CategoriesRepository $categories)
    {
        return $this->render('main/index.html.twig', [
            'categorieByMercerie' => $categories->findBy(['typeCategories'=>1 ]),
            'categorieByTissu' => $categories->findBy(['typeCategories' =>2])
        ]);
    }

    #[Route('/categorie/articles_mercerie/{id}', name: 'app_articles_mercerie')]
    public function articles_mercerie($id, ArticlesRepository $articles, CategoriesRepository $categoriesRepository )
    {
        return $this->render('main/categoriemercerie.html.twig', [
            'articles'=> $articles->findBy(['categories'=>$id]),
            'categorie' => $categoriesRepository->findOneBy(['id'=>$id])
        ]);
    }

    #[Route('/mention-legales', name: 'mentions_legales')]
    public function mentions_legales()
    {
        return $this->render('mentions_legales/mentions-legales-site-internet.html.twig');
    }
}