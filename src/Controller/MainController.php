<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use App\Repository\TypeCategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    // chemin page d'accueil
    #[Route('/', name: 'app_home')]
    public function index(TypeCategoriesRepository $typeCategoriesRepository)
    {
        // si l'utilisateur dont l'email n'est pas vérifié cherche à se connecter
        // un message flash s'affichera 
        if(!empty($this->getUser()) && $this->getUser()->isVerified() == 0){
            $this->addFlash('error', 'Veuillez valider votre mail afin de vous connecter');
        }
        
        return $this->render('main/index.html.twig', [
            'typeCategorie' => $typeCategoriesRepository->findAll()
        ]);
    }

    // fonction permettant d'afficher les articles selon la catégorie sélectionné
    #[Route('/categorie/articles_mercerie/{id}', name: 'app_articles_mercerie')]
    public function articles_mercerie($id, ArticlesRepository $articles, CategoriesRepository $categoriesRepository )
    {
        // recup les articles correspondant à la catégorie sélectionné par le user
        return $this->render('main/categoriemercerie.html.twig', [
            'articles'=> $articles->findBy(['categories'=>$id]),
            'categorie' => $categoriesRepository->findOneBy(['id'=>$id])
        ]);
    }
}