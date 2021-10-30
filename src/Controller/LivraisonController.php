<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Form\PaiementType;
use App\Manager\ArticlesManager;
use App\Repository\ArticlesRepository;
use App\Services\PanierService;
use App\Services\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LivraisonController extends AbstractController
{
    #[Route('/livraison', name: 'livraison')]
    public function livraison_facturation(Request $request, EntityManagerInterface $em): Response
    {
        $livraison = new Livraison();

        $livraison_form = $this->createForm(LivraisonType::class);
        $livraison_form-> handleRequest($request);

        if($livraison_form->isSubmitted() && $livraison_form->isValid() && !$livraison_form->isEmpty()){
            $em->persist($livraison);
            $em->flush();

            return $this->redirectToRoute("livraison_option");
        }

        return $this->render('livraison/index.html.twig', [
            'formLivraison' => $livraison_form->createView()
        ]);
    }

   
    #[Route('/livraison/optionlivraison', name: 'livraison_option')]
    public function option_livraison(SessionInterface $session, ArticlesRepository $articleRepository, Request $request): Response
    {
        $paniers = new PanierService();
        list ($dataPanier, $total) = $paniers->panier($session, $articleRepository);

        $form_paiement = $this->createForm(PaiementType::class);
        $form_paiement->handleRequest($request);
        
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
        
        
        return $this->render('livraison/optionlivraison.html.twig',[ 
            'dataPanier' => $dataPanier, 
            'total'=> $total , 
            'intent'=>$intent, 
            'form_paiement' => $form_paiement->createView()
            ]
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
