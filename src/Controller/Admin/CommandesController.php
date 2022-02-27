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
* @Route("/admin", name="commandes_")
*/
class CommandesController extends AbstractController
{
    #[Route('/commandes', name: 'options')]
    public function commandes(): Response
    {
        return $this->render('admin/commandes/index.html.twig');
    }
    
    #[Route('/commandes/list', name: 'list')]
    public function list_commandes(OrderRepository $orderRepository): Response
    {      
        return $this->render('admin/commandes/list.html.twig', [
            'nouvelles_commande' => $orderRepository->findBy(['orderStatus'=> 1]),
            'en_attente' => $orderRepository->findBy(['orderStatus'=> 2]),
            'commande_expediee' => $orderRepository->findBy(['orderStatus'=> 3])
        ]);
    }

    /**
    * @Route("/commandes/change/status/{id}", name="change_status")
    */
    public function changeStatus($id, Request $request, EntityManagerInterface $em, OrderRepository $orderRepository)
    {
        if($this->isCsrfTokenValid('update'.$id, $request->query->get('csrf')))
        {
            $status = $orderRepository->find($id);  
        
            $status_option = $this->createForm(OrderType::class,$status) ;
            $status_option->handleRequest($request);

            if($status_option->isSubmitted() && $status_option->isValid()){
                $status->setUpdatedAt(new \DateTime());

                $em->persist($status);
                $em->flush();
            
                if($status->getOrderStatus()->getStatus() == "nouvelle commande"){
                    return $this->redirectToRoute("commandes_nouvelles");
                }elseif($status->getOrderStatus()->getStatus() == "en attente"){
                    return $this->redirectToRoute("commandes_en_attente");
                }else{
                    return $this->redirectToRoute("commandes_expediee");
                }
            }
        }else{
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_admin');
        }
        
        return $this->render('admin/commandes/changeStatus.html.twig', [
            'status' => $status_option->createView()
        ]);
    }

    /**
    * @Route("/commandes/nouvelle", name="nouvelles")
    */
    public function nouvelles_commandes(OrderRepository $orderRepository): Response
    {      
        return $this->render('admin/commandes/nouvelle.html.twig', [
            'nouvelles_commande' => $orderRepository->findBy(['orderStatus'=> 1])
        ]);
    }

    /**
    * @Route("/commandes/enAttentes", name="en_attente")
    */
    public function enAttente(OrderRepository $orderRepository)
    {
        return $this->render('admin/commandes/enAttente.html.twig', [
            'cmdEnAttente' => $orderRepository->findBy(['orderStatus'=> 2])
        ]);
    }

    /**
    * @Route("/commandes/expediees", name="expediee")
    */
    public function livree(OrderRepository $orderRepository)
    {
        return $this->render('admin/commandes/expediee.html.twig', [
            'commande_expediee' => $orderRepository->findBy(['orderStatus'=> 3])
        ]);
    }
}