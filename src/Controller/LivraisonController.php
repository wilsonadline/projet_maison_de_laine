<?php

namespace App\Controller;

use App\Entity\Adresses;
use App\Form\AdressesType;
use App\Form\PaiementType;
use App\Services\PanierService;
use App\Repository\DelivryRepository;
use App\Repository\AdressesRepository;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderStatusRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LivraisonController extends AbstractController
{
    // fonction pour recup l'adresse du client
    #[Route('/adresse', name: 'adresse')]
    public function adresse_facturation(Request $request, EntityManagerInterface $em): Response
    {
        // j'instancie l'objet adresse
        $adresse = new Adresses();

        // Doctrine crée un form
        $adresse_form = $this->createForm(AdressesType::class, $adresse);
        // demande de traitement de la saisie du form
        $adresse_form->handleRequest($request);
        
        // si le form est envoyer , qu'il est valide et qu'il n'est pas vide
        if($adresse_form->isSubmitted() && $adresse_form->isValid() && !$adresse_form->isEmpty())
        {
            // intialiser l'heure de création
            $adresse->setCreatedAt(new \DateTime());
            
            // récupérer les informations saisies
            $em->persist($adresse);
            // envoyer les informations à la BDD
            $em->flush();
            
            // enfin la page dirigée vers celle indiqué ci-dessous
            return $this->redirectToRoute("livraison_option", ['id'=> $adresse->getId()]);
        }
        
        // création de la view du form affiché sur la page indiqué au render
        return $this->render('livraison/index.html.twig', [
            'adresse_form'=> $adresse_form->createView(),
        ]);
    }
   
    #[Route('/livraison/paiement/{id}', name:'livraison_option')]
    public function option_livraison($id,SessionInterface $session, AdressesRepository $adresses, ArticlesRepository $articleRepository,
     Request $request, EntityManagerInterface $em, DelivryRepository $delivryReposit): Response
    {

        // j'instancie l'objet Panier services avec en parametre l'Entity Manager
        $paniers = new PanierService($em);
        // creation 
        list ($dataPanier, $total) = $paniers->panier($session, $articleRepository);

        $form_paiement = $this->createForm(PaiementType::class);
        $form_paiement->handleRequest($request);
        
        if(isset($total) && !empty($total))
        {
            // permets de charger toute la bibliothèque de stripe
            require_once('../vendor/autoload.php');
            
            // on instancie Stripe
            \Stripe\Stripe::setApiKey('sk_test_51JlA15DnhjURuLLqEC9bDSLfcauQ5d4jltdhBlHnHj4y8kY1pqhyZc9dbFooWUSbUiffqJCnLZzK7hQjPaGjK5jS00V2NFZSc7');
            
            $intent = \Stripe\PaymentIntent::create([
                'amount' => $total*100,
                'currency' => 'eur'
            ]);
        }else{
            return $this->render('livraison/endSession.html.twig');
        }

        return $this->render('livraison/optionlivraison.html.twig',[ 
            'adresse' => $adresses->find($id),
            'dataPanier' => $dataPanier, 
            'total' => $total, 
            'intent' => $intent, 
            'form_paiement' => $form_paiement->createView(),
            'delivryModes' => $delivryReposit->findAll()
            ]
        );
    }

    /**
    * @Route("/validateOrder/{adresse_id}/{deliveryMode}", name="validateOrder", methods={"GET"})
    */
    public function validateOrder(AdressesRepository $adressesRepository, DelivryRepository $delivryRepository, $adresse_id, $deliveryMode,
        OrderStatusRepository $statusRepo, SessionInterface $session, ArticlesRepository $articleRepository, EntityManagerInterface $em
      )
    {
        $panier_service = new PanierService($em);
        list ($dataPanier)= $panier_service->panier($session, $articleRepository);
        $panier_service->gestionStock($dataPanier);

        $order = $panier_service->save_order($adressesRepository, $adresse_id, $statusRepo, $dataPanier, $delivryRepository, $deliveryMode, $session);

        return new JsonResponse($order->getId());
    }

    /**
    * @Route("succes/", name="succes", methods={"GET"})
    */
    public function succes(SessionInterface $session)
    {
        return $this->render('succes/succes.html.twig',[
            'orderId' => $session->get('order_id')
        ]);
    }

    /**
    * @Route("endSessoin", name="endSession", methods={"GET"})
    */
    public function endSession(SessionInterface $session)
    {
      return new JsonResponse( $session->remove("panier"));
    }
}