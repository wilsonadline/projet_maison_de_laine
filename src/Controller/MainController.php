<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Categories;
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
}
