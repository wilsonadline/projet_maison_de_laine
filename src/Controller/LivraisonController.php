<?php

namespace App\Controller;

use App\Entity\Adresses;
use App\Form\AdressesType;
use App\Form\PaiementType;
use App\Repository\ArticlesRepository;
use App\Repository\AdressesRepository;
use App\Repository\OrderRepository;
use App\Repository\OrderStatusRepository;
use App\Services\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LivraisonController extends AbstractController
{
    #[Route('/livraison', name: 'livraison')]
    public function livraison_facturation(Request $request, EntityManagerInterface $em, AdressesRepository $ad): Response
    {
        $livraison = new Adresses();

        $livraison_form = $this->createForm(AdressesType::class, $livraison);
        $livraison_form-> handleRequest($request);
        
        if($livraison_form->isSubmitted() && $livraison_form->isValid() && !$livraison_form->isEmpty())
        {
            $livraison->setCreatedAt(new \DateTime());
            
            $em->persist($livraison);
            $em->flush();
            return $this->redirectToRoute("livraison_option", ['id'=> $livraison->getId()]);
        }
        $ad = $livraison->getId();
        
        return $this->render('livraison/index.html.twig', [
            'formLivraison' => $livraison_form->createView()    
            // 'id'=> $ad
        ]);
    }
   
    #[Route('/livraison/optionlivraison/{id}', name: 'livraison_option')]
    public function option_livraison(SessionInterface $session, $id, AdressesRepository $adresses, ArticlesRepository $articleRepository, Request $request, EntityManagerInterface $em): Response
    {
        $paniers = new PanierService( $em);
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

       $ad = $adresses->find( $id);
    //    dd($ad);
    
        return $this->render('livraison/optionlivraison.html.twig',[ 
            'adresse'=> $ad,
            'dataPanier' => $dataPanier, 
            'total'=> $total , 
            'intent'=>$intent, 
            'form_paiement' => $form_paiement->createView()
            ]
        );
    }

    // /**
    //  * @Route("/facture/{id}", name="facture", methods={"POST"})
    //  */
    // public function facture(SessionInterface $session, ArticlesRepository $articleRepository, Request $request, EntityManagerInterface $em): Response
    // {
    //     $paniers = new PanierService( $em);
    //     list ($dataPanier) = $paniers->panier($session, $articleRepository);
    //     $paniers->gestionStock($dataPanier);

    //     return new JsonResponse(1);
    // }


    /**
     * @Route("/validateOrder/{adresse_id}", name="validateOrder", methods={"GET"})
     */
    public function validateOrder(AdressesRepository $adressesRepository, $adresse_id, OrderStatusRepository $statusRepo,
        SessionInterface $session, ArticlesRepository $articleRepository,
        EntityManagerInterface $em
      )
    {
        $panier_service = new PanierService( $em);
        list ($dataPanier)= $panier_service->panier($session, $articleRepository);
        $panier_service->gestionStock($dataPanier);

        $panier_service->save_order($adressesRepository, $adresse_id  , $statusRepo, $dataPanier);


       


        return $this->redirectToRoute("app_home");
    }
}

