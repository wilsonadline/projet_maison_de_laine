<?php

namespace App\Controller\Admin;

use App\Form\OrderType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Préfixe pour toutes les fonctions du controller Commandes
/**
* @Route("/admin", name="commandes_")
*/
class CommandesController extends AbstractController
{
    // Création du chemin vers la page index des commandes
    #[Route('/commandes', name: 'options')]
    public function commandes(): Response
    {
        return $this->render('admin/commandes/index.html.twig');
    }

    // Fonction permettant d'afficher la liste des commandes
    #[Route('/commandes/list', name: 'list')]
    public function list_commandes(OrderRepository $orderRepository): Response
    {
        return $this->render('admin/commandes/list.html.twig', [
            'nouvelles_commande' => $orderRepository->findBy(['orderStatus'=> 1]),
            'commandes_en_attentes' => $orderRepository->findBy(['orderStatus'=> 2]),
            'commandes_expediees' => $orderRepository->findBy(['orderStatus'=> 3])
        ]);
    }

    // Fonction permettant de modifier le status d'une commande
    /**
    * @Route("/commandes/change/status/{id}", name="change_status")
    */
    public function changeStatus($id, Request $request, EntityManagerInterface $em, OrderRepository $orderRepository)
    {
        // Si le Csrf token est valide
        if($this->isCsrfTokenValid('update'.$id, $request->query->get('csrf')))
        {
            // recupération de l'id 
            $status = $orderRepository->find($id);

            // Doctrine crée un form selon la commande à modifier
            $status_option = $this->createForm(OrderType::class,$status) ;
            // traitement de la saisie du form
            $status_option->handleRequest($request);

            if($status_option->isSubmitted() && $status_option->isValid())
            {
                // initialisation de l'heure de la modification
                $status->setUpdatedAt(new \DateTime());

                // indiquer a EM que cette entity devra etre enregistrer
                $em->persist($status);
                // enregristrement de l'entity dans la BDD
                $em->flush();

                if($status->getOrderStatus()->getStatus() == "nouvelle commande")
                {
                    // si le status a été modifié pour une nouvelle commande, 
                    // alors la page sera redirigé vers la page des nouvelles commande
                    $this->addFlash('update', 'La status a bien été modifié en "nouvelle commande"!');
                    return $this->redirectToRoute("commandes_nouvelles");
                }elseif($status->getOrderStatus()->getStatus() == "en attente")
                {
                    // si la modif corresponds a un statis en attente
                    // alors la redirection sera faite vers la page des commandes en attentes
                    $this->addFlash('update', 'La status a bien été modifié en "en attente"!');
                    return $this->redirectToRoute("commandes_en_attente");
                }else{
                    // si non, la redirection sera faite vers la page des commandes expediees
                    $this->addFlash('update', 'La status a bien été modifié en "expédiée"!');
                    return $this->redirectToRoute("commandes_expediee");
                }
            }
        }else{
            // si non un message flash de l'error sera affiché
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_admin');
        }

        // création de la view du form affiché sur la page indiqué au render
        return $this->render('admin/commandes/changeStatus.html.twig', [
            'status' => $status_option->createView()
        ]);
    }

    // Fonction permettant de recuperer les nouvelles commandes
    /**
    * @Route("/commandes/nouvelle", name="nouvelles")
    */
    public function nouvelles_commandes(OrderRepository $orderRepository): Response
    {
        // recup toutes les nouvelles commandes
        return $this->render('admin/commandes/nouvelle.html.twig', [
            'nouvelles_commande' => $orderRepository->findBy(['orderStatus'=> 1])
        ]);
    }

    // Fonction permettant de recuperer les commandes en attentes
    /**
    * @Route("/commandes/enAttentes", name="en_attente")
    */
    public function enAttente(OrderRepository $orderRepository)
    {
        // recup toutes les commandes en attentes
        return $this->render('admin/commandes/enAttente.html.twig', [
            'commandes_en_Attente' => $orderRepository->findBy(['orderStatus'=> 2])
        ]);
    }

    // Fonction permettant de recuperer les commandes expédiées
    /**
    * @Route("/commandes/expediees", name="expediee")
    */
    public function expediees(OrderRepository $orderRepository)
    {
        // recup toutes les commandes expediées
        return $this->render('admin/commandes/expediee.html.twig', [
            'commandes_expediees' => $orderRepository->findBy(['orderStatus'=> 3])
        ]);
    }
}