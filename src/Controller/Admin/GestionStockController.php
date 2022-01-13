<?php

namespace App\Controller\Admin;

use App\Form\OrderType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/admin/gestionstock", name="gestion_stock_")
*/
class GestionStockController extends AbstractController
{
    #[Route('/list/commandes', name: 'list_des_commandes')]
    public function list_commandes(OrderRepository $orderRepository): Response
    {      
        return $this->render('admin/gestionStock/listDesCommandes.html.twig', [
            'nouvelles_commande' => $orderRepository->findBy(['orderStatus'=> 1]),
            'en_attente' => $orderRepository->findBy(['orderStatus'=> 2]),
            'commande_expediee' => $orderRepository->findBy(['orderStatus'=> 3])
        ]);
    }

    /**
    * @Route("/change/status/{id}" , name="change_status")
    */
    public function changeStatus($id, Request $request, EntityManagerInterface $em, OrderRepository $orderRepository)
    {
        $status = $orderRepository->find($id);  
        
        $status_option = $this->createForm(OrderType::class,$status) ;
        $status_option->handleRequest($request);

        if($status_option->isSubmitted() && $status_option->isValid())
        {
            $status->setUpdatedAt(new \DateTime());

            $em->persist($status);
            $em->flush();
            
            if($status->getOrderStatus()->getStatus() == "nouvelle commande")
            {
                return $this->redirectToRoute("gestion_stock_nouvelles_commandes");
            }
            elseif($status->getOrderStatus()->getStatus() == "en attente")
            {
                return $this->redirectToRoute("gestion_stock_commande_en_attente");
            }
            else
            {
                return $this->redirectToRoute("gestion_stock_commande_expediee");
            }
        }
        
        return $this->render('admin/gestionStock/changeStatus.html.twig', [
            'status' => $status_option->createView()
        ]);
    }

    /**
    * @Route("/commandes/nouvelle" , name="nouvelles_commandes")
    */
    public function nouvelles_commandes(OrderRepository $orderRepository): Response
    {      
            return $this->render('admin/gestionStock/nouvelleCommande.html.twig', [
                'nouvelles_commande' => $orderRepository->findBy(['orderStatus'=> 1]),
        ]);
    }

    /**
    * @Route("/commandes/enAttentes" , name="commande_en_attente")
    */
    public function enAttente( OrderRepository $orderRepository)
    {
        return $this->render('admin/gestionStock/enAttente.html.twig', [
            'cmdEnAttente' => $orderRepository->findBy(['orderStatus'=> 2])
        ]);
    }

    /**
    * @Route("/commandes/expediees" , name="commande_expediee")
    */
    public function livree( OrderRepository $orderRepository)
    {
        return $this->render('admin/gestionStock/expediee.html.twig', [
            'commande_expediee' => $orderRepository->findBy(['orderStatus'=> 3])
        ]);
    }
}
