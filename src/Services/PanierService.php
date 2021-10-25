<?php
namespace App\Services;

use App\Repository\ArticlesRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{

    public function panier(SessionInterface $session, ArticlesRepository $articleRepository)
    {
        
        $panier = $session->get("panier", []);

        //  on "fabrique" les données
        $dataPanier = [] ; 
        $total = 0; 
        
        //  on "fabrique" les données
        $dataPanier = [] ; 
        $total = 0; 

        foreach($panier as $id =>$quantite)
        {
            //  on recupere l'article
            $article = $articleRepository->find($id);
            $dataPanier[] = [
                "article" => $article,
                "quantite"=>$quantite
            ];
            $total += $article->getPrix() * $quantite;

        }

        return [$dataPanier, $total];
    }

}