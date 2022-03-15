<?php
namespace App\Services;

use App\Entity\Order;
use App\Entity\OrderLine;
use App\Repository\AdressesRepository;
use App\Repository\ArticlesRepository;
use App\Repository\DelivryRepository;
use App\Repository\OrderStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{
    public $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    // fontion panier 
    public function panier(SessionInterface $session, ArticlesRepository $articleRepository)
    {
        // recup le panier dans la session
        $panier = $session->get("panier", []);

        // on initialise les éléments du panier
        $dataPanier = [] ; 
        $total = 0; 

        foreach($panier as $id => $quantite)
        {
            // on recupere l'article grace à son id
            $article = $articleRepository->find($id);

            // j'affecte l'article et la quantité de l'article dans le tableau dataPanier
            $dataPanier[] = [
                "article" => $article,
                "quantite" => $quantite,
            ];

            // je multiplie le prix de l'article par sa quantité et j'ajoute le resultat dans la variable total
            $total += $article->getPrix() * $quantite ;
        }

        return [$dataPanier, $total];
    }

    // fonction permettant de gérer le stock des articles
    public function gestionStock($panier) 
    {
        // dans la boucle j'affecte chaque panier en tant que produitpanier
        foreach($panier as $produitpanier)
        {
            // recup les attibuts article et quantité
            $article = $produitpanier["article"];
            $quantite = $produitpanier["quantite"];

            // j'accede au stock et si un article est acheté alors il sera retiré du stock
            $stock = $article->getStock();
            $article->setStock($stock - $quantite);
            $this->em->persist($article);
        }

        $this->em->flush();
    }

    // fonction permettant de sauvegarder les articles dans chaques lignes
    private function save_order_line($order, $dataPanier) 
    {
        $total = 0 ;

        // pour chaque dataPanier en tant que data 
        foreach($dataPanier as $data)
        {
            // instancier un nouvel objet OrderLine avec l'artcicle, la quantité acheté et le prix
            $order_line = new OrderLine();
            $order_line->setArticle($data['article']);
            $order_line->setPrix($data['article']->getPrix());
            $order_line->setQuantite($data['quantite']);
            $order_line->setOrders($order);

            $this->em->persist($order_line);
            $total += $order_line->getPrix() * $order_line->getQuantite();
        }

        $this->em->flush();
        return $total;
    }

    // fonction permettant de sauvegarder la commande
    public function save_order(AdressesRepository $repoAdresse , $adresse_id,
    OrderStatusRepository $statusRepo, $dataPanier, DelivryRepository $delivryRepository, $deliveryMode, SessionInterface $session)
    {
        // recup l'id de l'adresse
        $adresse = $repoAdresse->find($adresse_id);

        $status = $statusRepo->findOneBy(['status' => 'nouvelle commande']);
        
        $mode = $delivryRepository->findOneBy(["options" => $deliveryMode]);

        // creation de la cmd
        $order = new Order();
        $order->setCreatedAt(new \DateTime());
        $order->setUpdatedAt(new \DateTime());
        $order->setAdresse($adresse);
        $order->setDelivery($mode);
        $order->setOrderStatus($status);

        $this->em->persist($order);

        // ici on ajoute le prix de livraison au total existant
        $totalOrder = $this->save_order_line($order, $dataPanier);
        $order->setTotal($totalOrder + $mode->getPrice());
        $this->em->persist($order);
        $this->em->flush();

        $session->set('order_id', $order->getId());
        
        return $order;
    }
}