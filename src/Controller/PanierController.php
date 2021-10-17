<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/panier", name="panier_")
 */
class PanierController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, ArticlesRepository $articleRepository): Response
    {
        $panier = $session->get("panier", []);

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

        return $this->render('panier/index.html.twig', compact("dataPanier", "total")
        );
    }

    #[Route('/ajout/{id}', name: 'ajout')]
    public function panier_ajout( SessionInterface $session, Articles $article)
    {
        // on recupere le panier actuel
        $panier = $session->get("panier", []);
        $id = $article->getId();
        // $panier= array();
        
        if(!empty($panier[$id]))
        {
            $panier[$id]++;
        }
        else
        {
            $panier[$id] = 1;
        }
        // on sauvegarde dans la session
        $session->set("panier", $panier);
        // dd( $session);

        return $this->redirectToRoute("panier_index");
       
    }

    #[Route('/retirer/{id}', name: 'retirer')]
    public function panier_retirer( SessionInterface $session, Articles $article)
    {
        // on recupere le panier actuel
        $panier = $session->get("panier", []);
        $id = $article->getId();
        
        if(!empty($panier[$id]))
        {
            if($panier[$id] > 1)
            {
                $panier[$id]--;
            }
            else{
                unset($panier[$id]);
            }
        }
       
        // on sauvegarde dans la session
        $session->set("panier", $panier);
        // dd( $session);

        return $this->redirectToRoute("panier_index");
       
    }

    #[Route('/supprimer/{id}', name: 'supprimer')]
    public function panier_supprimer( SessionInterface $session, Articles $article)
    {
        // on recupere le panier actuel
        $panier = $session->get("panier", []);
        $id = $article->getId();
        
        if(!empty($panier[$id]))
        {
            unset($panier[$id]);
        }
       
        // on sauvegarde dans la session
        $session->set("panier", $panier);
        // dd( $session);

        return $this->redirectToRoute("panier_index");
       
    }

    #[Route('/vider', name: 'vider')]
    public function panier_vider( SessionInterface $session)
    {
       $session->remove("panier");

        return $this->redirectToRoute("panier_index");
       
    }
}
