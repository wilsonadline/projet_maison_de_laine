<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Categories;
// use App\Form\RechercheType;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CategoriesRepository $categories): Response
    {
        $categorieByMercerie = $categories->findBy(['typeCategories'=>1 ]);
        $categorieByTissu = $categories->findBy(['typeCategories' =>2]);

        return $this->render('main/index.html.twig', [
            'categorieByMercerie' => $categorieByMercerie,
            'categorieByTissu' => $categorieByTissu
        ]);
    }

    #[Route('/categorie/articles_mercerie/{id}', name: 'app_articles_mercerie')]
    public function articles_mercerie($id, ArticlesRepository $articles, CategoriesRepository $cat ): Response
    {
        $articles = $articles->findBy(['categories'=>$id]);
        $categorie = $cat->findOneBy(['id'=>$id]);

        return $this->render('main/categoriemercerie.html.twig', [
            'articles'=>$articles,
            'categorie' => $categorie
        ]);
    }

    #[Route('/mention-legales', name: 'mentions_legales')]
    public function mentions_legales(): Response
    {
        return $this->render('mentions_legales/mentions-legales-site-internet.html.twig');
    }
}
