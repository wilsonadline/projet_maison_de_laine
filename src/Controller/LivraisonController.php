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
        // j'instancie un nouvel objet adresse
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
            
            // indiquer a EM que cette entity devra etre enregistrer 
            $em->persist($adresse);
            // enregristrement de l'entity dans la BDD
            $em->flush();
            
            // enfin la page dirigée vers celle indiqué ci-dessous
            return $this->redirectToRoute("livraison_option", ['id'=> $adresse->getId()]);
        }

        // création de la view du form affiché sur la page indiqué au render
        return $this->render('livraison/index.html.twig', [
            'adresse_form'=> $adresse_form->createView(),
        ]);
    }

    // fonction pour valider le paiement
    #[Route('/livraison/paiement/{id}', name:'livraison_option')]
    public function option_livraison($id,SessionInterface $session, AdressesRepository $adresses, ArticlesRepository $articleRepository,
     Request $request, EntityManagerInterface $em, DelivryRepository $delivryReposit): Response
    {
        // j'instancie l'objet PanierService avec en parametre l'Entity Manager
        $paniers = new PanierService($em);
        // creation d'un array contenant les articles, leur quantités et le montant total
        list ($dataPanier, $total) = $paniers->panier($session, $articleRepository);

        // creation et gestion du formulaire paiement 
        $form_paiement = $this->createForm(PaiementType::class);
        $form_paiement->handleRequest($request);
        
        // si total exist et qu'il n'est pas vide
        if(isset($total) && !empty($total))
        {
            // charger toute la bibliothèque de stripe
            require_once('../vendor/autoload.php');
            
            // instancier Stripe
            \Stripe\Stripe::setApiKey('sk_test_51JlA15DnhjURuLLqEC9bDSLfcauQ5d4jltdhBlHnHj4y8kY1pqhyZc9dbFooWUSbUiffqJCnLZzK7hQjPaGjK5jS00V2NFZSc7');

            // creer une intention de paiement
            // qui contient le total en cents*100 + la devise de la monnaie
            $intent = \Stripe\PaymentIntent::create([
                'amount' => $total*100,
                'currency' => 'eur'
            ]);
        }else{
            // si le panier est vide la page sera redirigé
            // vers une page indiquant que le panier est expiré
            return $this->redirectToRoute('expCart');
        }

        // envoie des éléments à la page indiqué au render
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

    // fonction permettant d'appliquer l'enregistrement de la commande
    /**
    * @Route("/validateOrder/{adresse_id}/{deliveryMode}", name="validateOrder", methods={"GET"})
    */
    public function validateOrder(AdressesRepository $adressesRepository, DelivryRepository $delivryRepository, $adresse_id, $deliveryMode,
        OrderStatusRepository $statusRepo, SessionInterface $session, ArticlesRepository $articleRepository, EntityManagerInterface $em
      )
    {
        // intancier l'objet PanierService qui a en parametre Entity Manager
        $panier_service = new PanierService($em);
        // Je ne suis pas sure
        // J'accede à la fonction panier et ses parametres
        list ($dataPanier)= $panier_service->panier($session, $articleRepository);
        $panier_service->gestionStock($dataPanier);

        // de mon objet panier_service, j'appelle ma fonction save_order et ses parametres
        $panier_service->save_order($adressesRepository, $adresse_id, $statusRepo, $dataPanier, $delivryRepository, $deliveryMode, $session);

        // Json renvoie le status success de l'opération
        $response = new JsonResponse();
        $response->setStatusCode(JsonResponse::HTTP_OK);

        return $response;
    }


    // fonction succes où l'utilisateur aura la possibilité de télécharger sa facture
    /**
    * @Route("succes/", name="succes", methods={"GET"})
    */
    public function succes(SessionInterface $session)
    {
        return $this->render('succes/succes.html.twig',[
            'orderId' => $session->get('order_id')
        ]);
    }

    // fonction fin de session afin d'effacer le panier à la fin du paiement.
    /**
    * @Route("removePanier", name="removePanier", methods={"GET"})
    */
    public function removePanier(SessionInterface $session)
    {
        $session->remove("panier");
        // Json renvoie le status success de l'opération
        $response = new JsonResponse();
        $response->setStatusCode(JsonResponse::HTTP_OK);

        return $response;
    }

    // fonction permettant d'afficher la page de l'expiration du panier
    /**
    * @Route("expCart", name="expCart")
    */
    public function expCart()
    {
        return $this->render('expCart/expCart.html.twig');
    }
}