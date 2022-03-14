<?php

namespace App\Controller\Admin;

use App\Entity\TypeCategories;
use App\Form\TypeCategoriesType;
use App\Repository\TypeCategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Préfixe pour toutes les fonctions du controller Type Categories
/**
* @Route("/admin", name="type_categories_")
*/
class TypeCategoriesController extends AbstractController
{
    // Création du chemin vers la page index des types de catégories
    #[Route('/typeCategories', name: 'options')]
    public function typeCategories(): Response
    {
        return $this->render('admin/gestionStock/type_categories/index.html.twig');
    }

    // Fonction permettant d'ajouter un type de catégorie
    #[Route("/typeCategories/ajout", name: "ajout")]
    public function typeCategoriesAjout(Request $request, EntityManagerInterface $em): Response
    {
        // j'instancie un nouvel objet type de catégorie
        $typeCategoriesAjout = new TypeCategories();

        // Doctrine crée un form
        $typeCategoriesAjout_form = $this->createForm(TypeCategoriesType::class, $typeCategoriesAjout);
        // demande de traitement de la saisie du form
        $typeCategoriesAjout_form->handleRequest($request);

        // si le form est soumis et qu'il est valide
        if($typeCategoriesAjout_form->isSubmitted() && $typeCategoriesAjout_form->isValid())
        {
            // alors initialiser une heure de création
            $typeCategoriesAjout->setCreatedAt(new \DateTime());

            // indiquer a EM que cette entity devra etre enregistrer
            $em->persist($typeCategoriesAjout);
            // enregristrement de l'entity dans la BDD
            $em->flush();

            // envoi d'un message flash à l'enregistrement des infos dans la BDD
            $this->addFlash('add', 'Le type de catégorie a bien été ajouté !');
            // redirection de la page vers la page ci-dessous
            return $this->redirectToRoute('type_categories_list');
        }

            // création de la view du form affiché sur la page indiqué au render
            return $this->render('admin/gestionStock/type_categories/ajout.html.twig', [
            'typeCategoriesAjout' => $typeCategoriesAjout_form->createView()
        ]);
    }

    // Fonction permettant de modifier le type de catégorie
    #[Route("/typeCategories/modifier/{id}", name: "modifier")]
    public function typeCategoriesModifier($id, Request $request, EntityManagerInterface $em, TypeCategoriesRepository $typeCategoriesRepository): Response
    {
        // Si le Csrf token est valide
        if($this->isCsrfTokenValid('update'.$id, $request->query->get('csrf')))
        {  
            // recupération de l'id 
            $typeCategoriesModifier = $typeCategoriesRepository->find($id);
            
            // Doctrine crée un form selon le type de catégorie à modifier
            $typeCategoriesModifier_form = $this->createForm(TypeCategoriesType::class, $typeCategoriesModifier);
            // traitement de la saisie du form
            $typeCategoriesModifier_form->handleRequest($request);

            if($typeCategoriesModifier_form->isSubmitted() && $typeCategoriesModifier_form->isValid())
            {
                // initialisation de l'heure de la modification
                $typeCategoriesModifier->setUpdatedAt(new \DateTime());

                // indiquer a EM que cette entity devra etre enregistrer
                $em->persist($typeCategoriesModifier);
                // enregristrement de l'entity dans la BDD
                $em->flush();

                // si toutes ces étapes sont validées, affichage d'un message flash de l'update
                $this->addFlash('update', 'Le type de catégorie a bien été modifié !');
                return $this->redirectToRoute('type_categories_list');
            }
        }else{
            // si non un message flash de l'error sera affiché
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_admin');
        }

        // création de la view du form affiché sur la page indiqué au render
        return $this->render('admin/gestionStock/type_categories/modifier.html.twig', [
            'typeCategoriesModifier' => $typeCategoriesModifier_form->createView()
        ]);
    }

    // Fonction permettant de supprimer un type de catégorie
    #[Route("/typeCategories/delete/{id}", name: "delete")]
    public function typeCategoriesDelete($id, EntityManagerInterface $em, 
    Request $request, TypeCategoriesRepository $typeCategoriesRepository): Response
    {
        // Si le Csrf token est valide
        if($this->isCsrfTokenValid('delete'.$id, $request->query->get('csrf')))
        {
            // recupération de l'id 
            $typeCategoriesDelete = $typeCategoriesRepository->find($id);

            // L'entity Manager retient le type de catégorie à supprimer
            $em->remove($typeCategoriesDelete);
            // puis le supprime de la BDD
            $em->flush();
        
            // Message flash si le type de catégorie a bien été supprimé de la BDD
            $this->addFlash('delete', 'Le type de catégorie a bien été supprimé !');
            return $this->redirectToRoute('type_categories_list');
        }else{
            // Message flash si echec de la suppression
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_admin');
        }
    }

    // Fonction permettant d'afficher la liste des types de catégories
    #[Route("/typeCategories/list", name: "list")]
    public function typeCategoriesList(TypeCategoriesRepository $typeCategoriesList): Response
    {
        // Find all récupère tous les types de catégories
        return $this->render('admin/gestionStock/type_categories/list.html.twig', [
            'typeCategoriesList' => $typeCategoriesList->findAll()
        ]);
    }
}