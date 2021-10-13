<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Form\RechercheType;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CategoriesRepository $categories, ArticlesRepository $articles, Request $request): Response
    {
        // $article = $articles->findAll();

        $categorieByMercerie = $categories->findBy(['typeCategories'=>1 ]);
        $categorieByTissu = $categories->findBy(['typeCategories' =>2]);

        $form_recherche = $this->createForm(RechercheType::class);

        $recherche = $form_recherche->handleRequest($request);
        
        if($form_recherche->isSubmitted() && $form_recherche->isValid())
        {
            // on recherche les articles correspondant aux mots clès
            $categorieByMercerie = $categories->recherche(
                $recherche->get('mots')->getData(),
                $recherche->get('article')->getData()
            );
            // $categorieByTissu = $categories->recherche($recherche->get('mots')->getData());
        }

       

        return $this->render('main/index.html.twig', [
            'categorieByMercerie' => $categorieByMercerie,
            'categorieByTissu' => $categorieByTissu,
            'form_recherche' => $form_recherche->createView()
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
