<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Préfixe pour toutes les fonctions du controller Catégories
/**
* @Route("/admin", name="categories_")
*/
class CategoriesController extends AbstractController
{
    // Création du chemin vers la page index des catégories
    #[Route('/categories', name: 'options')]
    public function categories(): Response
    {
        return $this->render('admin/gestionStock/categories/index.html.twig');
    }

    // Fonction permettant d'ajouter une catégorie
    #[Route("/categories/ajout", name: "ajout")]
    public function categoriesAjout(Request $request, EntityManagerInterface $em): Response
    {
        // Création nouvelle catégorie
        $categoriesAjout = new Categories();
      
        // Doctrine crée un form
        $categoriesAjout_form = $this->createForm(CategoriesType::class, $categoriesAjout);
        // demande de traitement de la saisie du form
        $categoriesAjout_form->handleRequest($request);

        // si le form est soumis et qu'il est valide
        if($categoriesAjout_form->isSubmitted() && $categoriesAjout_form->isValid())
        {
            // alors initialiser une heure de création
            $categoriesAjout->setCreatedAt(new \DateTime());
            
            // L'entity Manager retient les infos saisies
            $em->persist($categoriesAjout);
            // puis les envoie à la BDD
            $em->flush();
            
            // envoi d'un message flash à l'enregistrement des infos dans la BDD
            $this->addFlash('add', 'La catégorie a bien été ajouté !');
            // redirection de la page vers la page ci-dessous
            return $this->redirectToRoute('categories_list');
        }

        // création de la view du form affiché sur la page indiqué au render
        return $this->render('admin/gestionStock/categories/ajout.html.twig', [
            'categoriesAjout' => $categoriesAjout_form->createView()
        ]);
    }

    // Fonction permettant de modifier la catégorie
    #[Route("/categories/modifier/{id}", name: "modifier")]
    public function categoriesModifier($id, Request $request, EntityManagerInterface $em, CategoriesRepository $categoriesRepository): Response
    {
        // Si le Csrf token est valide
        if($this->isCsrfTokenValid('update'.$id, $request->query->get('csrf')))
        {
            // recupération de l'id 
            $categoriesModifier = $categoriesRepository->find($id);

            // Doctrine crée un form selon la catégorie à modifier
            $categoriesModifier_form = $this->createForm(CategoriesType::class, $categoriesModifier);
            // traitement de la saisie du form
            $categoriesModifier_form->handleRequest($request);

            if($categoriesModifier_form->isSubmitted() && $categoriesModifier_form->isValid())
            {
                // initialisation de l'heure de la modification
                $categoriesModifier->setUpdatedAt(new \DateTime());

                // L'entity Manager retient les infos saisies
                $em->persist($categoriesModifier);
                // puis les envoie à la BDD
                $em->flush();

                // si toutes ces étapes sont validées, affichage d'un message flash de l'update
                $this->addFlash('update', 'La catégorie a bien été modifié !');
                return $this->redirectToRoute('categories_list');
            }
        }else{
            // si non un message flash de l'error sera affiché
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_admin');
        }

        // création de la view du form affiché sur la page indiqué au render
        return $this->render('admin/gestionStock/categories/modifier.html.twig', [
            'categoriesModifier' => $categoriesModifier_form->createView()
        ]);
    }

    // Fonction permettant de supprimer une catégorie
    #[Route("/categories/delete/{id}", name: "delete")]
    public function categoriesDelete($id, EntityManagerInterface $em, Request $request, CategoriesRepository $categoriesRepository): Response
    {
        // Si le Csrf token est valide
        if($this->isCsrfTokenValid('delete'.$id, $request->query->get('csrf')))
        {
            // recupération de l'id 
            $categoriesDelete = $categoriesRepository->find($id);

            // L'entity Manager retient la catégorie  à supprimer
            $em->remove($categoriesDelete);
            // puis le supprime de la BDD
            $em->flush();

            // Message flash si la catégorie à bien été supprimé de la BDD
            $this->addFlash('delete', 'La catégorie a bien été supprimé !');
            return $this->redirectToRoute('categories_list');
        }else{
            // Message flash si echec de la suppression
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_admin');
        }
    }

    // Fonction permettant d'afficher la liste des catégories
    #[Route("/categories/list", name: "list")]
    public function categoriesList(CategoriesRepository $categoriesList): Response
    {
        // Find all récupère tous les catégories
        return $this->render('admin/gestionStock/categories/list.html.twig', [
            'categoriesList' => $categoriesList->findAll()
        ]);
    }
}