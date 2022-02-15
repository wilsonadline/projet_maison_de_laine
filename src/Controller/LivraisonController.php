<?php

namespace App\Controller;

use App\Entity\Adresses;
use App\Entity\Users;
use App\Form\AdressesType;
use App\Form\PaiementType;
use App\Services\PanierService;
use App\Repository\OrderRepository;
use App\Repository\DelivryRepository;
use App\Repository\AdressesRepository;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderStatusRepository;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LivraisonController extends AbstractController
{
    #[Route('/adresse', name: 'adresse')]
    public function adresse_facturation(Request $request, EntityManagerInterface $em): Response
    {
        $adresse = new Adresses();

        $adresse_form = $this->createForm(AdressesType::class, $adresse);

        $adresse_form->handleRequest($request);
        
        if($adresse_form->isSubmitted() && $adresse_form->isValid() && !$adresse_form->isEmpty())
        {
            $adresse->setCreatedAt(new \DateTime());
            
            $em->persist($adresse);
            $em->flush();
            
            return $this->redirectToRoute("livraison_option", ['id'=> $adresse->getId()]);
        }
        
        return $this->render('livraison/index.html.twig', [
            'adresse_form'=> $adresse_form->createView(),
        ]);
    }
   
    #[Route('/livraison/optionlivraison/{id}', name:'livraison_option')]
    public function option_livraison($id,SessionInterface $session, AdressesRepository $adresses, ArticlesRepository $articleRepository,
     Request $request, EntityManagerInterface $em, DelivryRepository $delivryReposit): Response
    {

        $paniers = new PanierService($em);
        list ($dataPanier, $total) = $paniers->panier($session, $articleRepository);

        $form_paiement = $this->createForm(PaiementType::class);
        $form_paiement->handleRequest($request);
        
        if(isset($total) && !empty($total))
        {
            // permets de charger toute la bibliothèque de stripe
            require_once('../vendor/autoload.php');
            
            //  on instancie Stripe
            \Stripe\Stripe::setApiKey('sk_test_51JlA15DnhjURuLLqEC9bDSLfcauQ5d4jltdhBlHnHj4y8kY1pqhyZc9dbFooWUSbUiffqJCnLZzK7hQjPaGjK5jS00V2NFZSc7');
            
            $intent = \Stripe\PaymentIntent::create([
                'amount' => $total*100,
                'currency' => 'eur'
            ]);
        }else{
            return $this->render('livraison/endSession.html.twig');
        }

        return $this->render('livraison/optionlivraison.html.twig',[ 
            'adresse'=>  $adresses->find($id),
            'dataPanier' => $dataPanier, 
            'total'=> $total, 
            'intent'=>$intent, 
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