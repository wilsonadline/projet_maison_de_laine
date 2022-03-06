<?php

namespace App\Controller\Admin;

use App\Entity\Delivry;
use App\Form\DeliveryType;
use App\Repository\DelivryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Préfixe pour toutes les fonctions du controller Delivery
/**
* @Route("/admin", name="delivery_")
*/
class DeliveryController extends AbstractController
{
    // Création du chemin vers la page index des delivery
    #[Route('/delivery', name: 'options')]
    public function delivery(): Response
    {
        return $this->render('admin/delivery/index.html.twig');
    }

    // Fonction permettant d'afficher la liste des moyens de livraisons
    #[Route('/delivery/list', name: 'options_list')]
    public function delivery_options_list(DelivryRepository $delivryRepository): Response
    {
        return $this->render('admin/delivery/list.html.twig', [
            'delivery_list' => $delivryRepository->findAll()
        ]);
    }

    // Fonction permettant d'ajouter un mpyen de livraison
    #[Route('/delivery/ajout', name: 'options_add')]
    public function delivery_options_add(Request $request, EntityManagerInterface $em): Response
    {
        // Création nouveau delivery
        $add_delivery = new Delivry();

        // Doctrine crée un form
        $add_delivery_form = $this->createForm(DeliveryType::class , $add_delivery);
        // traitement de la saisie du form
        $add_delivery_form->handleRequest($request);

        // si le form est soumis et qu'il est valide
        if($add_delivery_form->isSubmitted()&& $add_delivery_form->isValid())
        {
            // récupérer les informations saisies
            $em->persist($add_delivery);
            // envoyer les informations à la BDD
            $em->flush();

            // envoi d'un message flash à l'enregistrement des infos dans la BDD
            $this->addFlash('add', 'L\'option a bien été ajouté !');
            // redirection de la page vers la page ci-dessous
            return $this->redirectToRoute('delivery_options_list');
        }

        // création de la view du form affiché sur la page indiqué au render
        return $this->render('admin/delivery/ajout.html.twig', [
            'add_delivery' => $add_delivery_form->createView()
        ]);
    } 
    
    // Fonction permettant de modifier le moyen de livraison
    #[Route('/delivery/modifier/{id}', name: 'options_update')]
    public function delivery_options_update(DelivryRepository $delivryRepository, $id, Request $request, EntityManagerInterface $em): Response
    {
        // Si le Csrf token est valide
        if($this->isCsrfTokenValid('update'.$id, $request->query->get('csrf')))
        {     
            // Doctrine crée le form
            $update_delivery_form = $this->createForm(DeliveryType::class , $delivryRepository->find($id));
            // traitement de la saisie du form
            $update_delivery_form->handleRequest($request);

            if($update_delivery_form->isSubmitted() && $update_delivery_form->isValid()){
                
                // L'entity Manager retient les infos saisies
                $em->persist($delivryRepository->find($id));
                // puis les envoie à la BDD
                $em->flush();
            
                // si toutes ces étapes sont validées, affichage d'un message flash de l'update
                $this->addFlash('update', 'L\'option a bien été modifié !');
                return $this->redirectToRoute('delivery_options_list');
            }
        }else{
            // si non un message flash de l'error sera affiché
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_admin');
        }

        // création de la view du form affiché sur la page indiqué au render
        return $this->render('admin/delivery/modifier.html.twig', [
            'update_delivery' => $update_delivery_form->createView()
        ]);
    } 
    
    // Fonction permettant de supprimer le moyen de livraison
    #[Route('/delivery/supprimer/{id}', name: 'options_delete')]    
    public function delivery_options_delete($id, EntityManagerInterface $em, DelivryRepository $delivryRepository, Request $request): Response
    {
        // Si le Csrf token est valide
        if($this->isCsrfTokenValid('delete'.$id, $request->query->get('csrf')))
        {
            // L'entity Manager retient le moyen de livraison à supprimer
            $em->remove($delivryRepository->find($id));
            // puis le supprime de la BDD
            $em->flush();

            // Message flash si le suppression a bien été fait de la BDD
            $this->addFlash('delete', 'L\'option a bien été supprimé !');
            return $this->redirectToRoute('delivery_options_list');
        }else{
            // Message flash si echec de la suppression
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_admin');
        }
    }
}