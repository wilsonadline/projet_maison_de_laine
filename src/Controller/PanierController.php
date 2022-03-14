<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use App\Services\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

// Préfixe pour toutes les fonctions de la class PanierController
/**
* @Route("/panier", name="panier_")
*/
class PanierController extends AbstractController
{
    // fonction panier
    #[Route('/', name: 'index')]
    public function panier(SessionInterface $session, ArticlesRepository $articleRepository, EntityManagerInterface $em): Response
    {
        // j'instancie un nouvel objet panierService
        $paniers = new PanierService($em);
        // creation d'un array contenant les articles, leur quantités et le montant total
        list ($dataPanier, $total) = $paniers->panier($session, $articleRepository);

        // ici compact permet de creer un tableau avec des variables et leur valeurs
        return $this->render('panier/panier.html.twig', compact("dataPanier", "total")
        );
    }

    // fonction permettant d'incrementer la quantité d'un article existant dans le panier
    #[Route('/ajout/{id}', name: 'ajout')]
    public function panier_ajout( SessionInterface $session, Articles $article)
    {
        // on recupere le panier existant
        $panier = $session->get("panier", []);
        // id de l'article dont on souhaite incrementer la quantité
        $id = $article->getId();
        
        // si le panier ne contient pas l'article correspondant à l'id 
        // alors initier la quantité de cet article à 1 dans la panier
        if(empty($panier[$id]))
        {
            $panier[$id] = 1;
        }else{
            // si non on peut incrementer la quantité de l'article de +1 
            $panier[$id]++;
        }
        
        // on sauvegarde dans la session
        $session->set("panier", $panier);

        // on reste sur la page panier
        return $this->redirectToRoute("panier_index");
    }

    // fonction permettant de soustraire un article deja existant dans le panier si sa quantité n'est pas de 0
    #[Route('/retirer/{id}', name: 'retirer')]
    public function panier_retirer( SessionInterface $session, Articles $article)
    {
        // on recupere le panier actuel
        $panier = $session->get("panier", []);
        // recup l'id de l'article
        $id = $article->getId();
        
        // si le panier contient bien l'id de l'article
        if(!empty($panier[$id]))
        {
            // et si la quantité de l'article est supérieur à 1
            if($panier[$id] > 1){
                // alors on décrementer la quantité de l'article de -1
                $panier[$id]--;
            }else{// sinon il retire l'article du panier
                unset($panier[$id]);
            }
        }
       
        // on sauvegarde dans la session
        $session->set("panier", $panier);

        // on reste sur la page panier
        return $this->redirectToRoute("panier_index");
    }

    // fonction permettant de supprimer la ligne de l'article deja existant dans le panier
    #[Route('/supprimer/{id}', name: 'supprimer')]
    public function panier_supprimer( SessionInterface $session, Articles $article)
    {
        // on recupere le panier actuel
        $panier = $session->get("panier", []);
        $id = $article->getId();
        
        // si le panier contient bien l'id de l'article
        if(!empty($panier[$id]))
        {
            // on peut supprimer cet article du panier
            unset($panier[$id]);
        }
        
        // on sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("panier_index");
    }

    // fonction permettant de vider le panier entierement
    #[Route('/vider', name: 'vider')]
    public function panier_vider( SessionInterface $session)
    {
        // on efface les données de tout le panier
        $session->remove("panier");

        return $this->redirectToRoute("panier_index");
    }
}