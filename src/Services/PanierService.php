<?php
namespace App\Services;

use App\Entity\Adresses;
use App\Entity\Delivry;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\OrderStatus;
use App\Repository\AdressesRepository;
use App\Repository\ArticlesRepository;
use App\Repository\DelivryRepository;
use App\Repository\OrderRepository;
use App\Repository\OrderStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Cast\Double;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{
    public $em;
    public function __construct( EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function panier(SessionInterface $session, ArticlesRepository $articleRepository)
    {
        
        $panier = $session->get("panier", []);

        //  on initialise du panier 
        $dataPanier = [] ; 
        $total = 0; 
        
        foreach($panier as $id =>$quantite)
        {
            //  on recupere l'article
            $article = $articleRepository->find($id);
            $dataPanier[] = [
                "article" => $article,
                "quantite"=> $quantite,
            
            ];
            $total += $article->getPrix() * $quantite ;
        }

        return [$dataPanier, $total];
    }

    public function gestionStock($panier) {
            foreach($panier as $produitpanier){
                
                $article = $produitpanier["article"];
                $quantite = $produitpanier["quantite"];
                
                $stock =  $article->getStock();
                $article->setStock($stock - $quantite);
                $this->em->persist($article);
            
            }
            $this->em->flush();
    }

    private function save_order_line( $order ,  $dataPanier) {
  
        $total = 0 ;
        foreach($dataPanier as $data)
        {
            $order_line = new OrderLine();
            $order_line->setArticle($data['article']);  
            $order_line->setPrix($data['article']->getPrix());           
            $order_line->setQuantite($data['quantite']);
            $order_line->setOrders($order);   
            
            $this->em->persist($order_line);
            $total +=  $order_line->getPrix() * $order_line->getQuantite();
        }
        $this->em->flush();
        return $total;
    }

    public function save_order(AdressesRepository $repoAdresse , $adresse_id,
        OrderStatusRepository $statusRepo, 
        $dataPanier, DelivryRepository $delivryRepository, $deliveryMode ) {
       
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
        // $order->setDelivry($deliveryMode);
        
        // $order->getTotal();
        $this->em->persist($order);
        // $this->em->flush();
        
        $totalOrder =  $this->save_order_line($order, $dataPanier);
        
        $order->setTotal($totalOrder + $mode->getPrice() );
        $this->em->persist($order);
        $this->em->flush();
        return $order;
    }
}