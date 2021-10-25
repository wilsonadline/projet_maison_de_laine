<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Manager\ArticlesManager;
use App\Repository\ArticlesRepository;
use App\Services\PanierService;
use App\Services\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LivraisonController extends AbstractController
{
    #[Route('/livraison', name: 'livraison')]
    public function livraison_facturation(): Response
    {
        return $this->render('livraison/index.html.twig', [
            'controller_name' => 'LivraisonController',
        ]);
    }

   
    #[Route('/livraison/optionlivraison', name: 'livraison_option')]
    public function option_livraison(SessionInterface $session, ArticlesRepository $articleRepository): Response
    {
        $paniers = new PanierService();
        list ($dataPanier, $total) = $paniers->panier($session, $articleRepository);

        if(isset($total) && !empty($total)){
            // permets de charger toute la bibliothèque de stripe
            require_once('../vendor/autoload.php');
            
            
            //  on instancie Stripe
            \Stripe\Stripe::setApiKey('sk_test_51JlA15DnhjURuLLqEC9bDSLfcauQ5d4jltdhBlHnHj4y8kY1pqhyZc9dbFooWUSbUiffqJCnLZzK7hQjPaGjK5jS00V2NFZSc7');
            
            $intent = \Stripe\PaymentIntent::create([
                'amount' => $total*100,
                'currency' => 'eur'
            ]);
        }else{
            return $this->render('livraison/refuspaiement.html.twig');
        }
        
         
        return $this->render('livraison/optionlivraison.html.twig', compact("dataPanier", "total", "intent")
        );
    }


}


 // #[Route('/livraison/optionlivraison', name: 'livraison_option')]
    // public function option_livraison(): Response
    // {
    //     return $this->render('livraison/optionlivraison.html.twig', [
    //         'controller_name' => 'LivraisonController',
    //     ]);
    // }
    
    // #[Route('/livraison/paiement/{id}', name: 'livraison_paiement')]
    // public function paiement(Articles $articles, SessionInterface $session, ArticlesRepository $articleRepository, ArticlesManager $articlesManager): Response
    // {
    //     $paniers = new PanierService();
    //     list ($dataPanier, $total) = $paniers->panier($session, $articleRepository);
          
    //      return $this->render('livraison/paiement.html.twig',[
    //         'dataPanier' => $dataPanier,
    //         'total' => $total,
    //         'intentSecret' => $articlesManager->intentSecret($articles)
    //     ]);
    // }
