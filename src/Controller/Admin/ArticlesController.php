<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Préfixe pour toutes les fonctions du controller Articles
/**
* @Route("/admin", name="articles_")
*/
class ArticlesController extends AbstractController
{
    // Création du chemin vers la page index des articles
    #[Route('/articles', name: 'options')]
    public function articles(): Response
    {
        return $this->render('admin/gestionStock/articles/index.html.twig');
    }

    // Fonction permettant d'ajouter un article
    #[Route("/articles/ajout", name: "ajout")]
    public function articlesAjout(Request $request, EntityManagerInterface $em): Response
    {
        // Création nouveau article
        $articleAjout = new Articles();

        // j'appelle doctrine pour créer un form 
        $articlesAjout_form = $this->createForm(ArticlesType::class, $articleAjout);
        // demande de traitement de la saisie du form
        $articlesAjout_form->handleRequest($request);

        // si le form est soumis et qu'il est valide
        if($articlesAjout_form->isSubmitted() && $articlesAjout_form->isValid())
        {
            // alors initialiser une heure de création
            $articleAjout->setCreatedAt(new \DateTime());

            // récupérer les informations saisies
            $em->persist($articleAjout);
            // envoyer les informations à la BDD
            $em->flush();

            // envoi d'un message flash à l'enregistrement des infos dans la BDD
            $this->addFlash('add', 'L\'article a bien été ajouté !');
            // redirection de la page vers la page ci-dessous
            return $this->redirectToRoute('articles_list');
        }

        // création de la view du form affiché sur la page indiqué au render
        return $this->render('admin/gestionStock/articles/ajout.html.twig', [
            'articlesAjout' => $articlesAjout_form->createView()
        ]);
    }

    // Fonction permettant de modifier l'article
    #[Route("/articles/modifier/{id}", name: "modifier")]
    public function articlesModifier($id , Request $request, EntityManagerInterface $em, ArticlesRepository $articlesRepository): Response
    {
        // Si le Csrf token est valide
        if($this->isCsrfTokenValid('update'.$id, $request->query->get('csrf')))
        {
            // recupération de l'id 
            $articlesModifier = $articlesRepository->find($id);

            // Doctrine crée un form selon l'article à modifier
            $articlesModifier_form = $this->createForm(ArticlesType::class, $articlesModifier);
            // traitement de la saisie du form
            $articlesModifier_form->handleRequest($request);

            if($articlesModifier_form->isSubmitted() && $articlesModifier_form->isValid())
            {
                // initialisation de l'heure de la modification
                $articlesModifier->setUpdatedAt(new \DateTime());

                // L'entity Manager retient les infos saisies
                $em->persist($articlesModifier);
                // puis les envoie à la BDD
                $em->flush();

                // si toutes ces étapes sont validées, affichage d'un message flash de l'update
                $this->addFlash('update', 'L\'article a bien été modifié !');
                return $this->redirectToRoute('articles_list');
            }
        }else{
            // si non un message flash de l'error sera affiché
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_admin');
        }

        // création de la view du form affiché sur la page indiqué au render
        return $this->render('admin/gestionStock/articles/modifier.html.twig', [
            'articlesModifier' => $articlesModifier_form->createView()
        ]);
    }

    // Fonction permettant de supprimer un article
    #[Route("/articles/delete/{id}", name: "delete")]
    public function articlesDelete($id, EntityManagerInterface $em, Request $request, ArticlesRepository $articlesRepository): Response
    {
        // Si le Csrf token est valide
        if($this->isCsrfTokenValid('delete'.$id, $request->query->get('csrf')))
        {
            // recupération de l'id 
            $articlesDelete = $articlesRepository->find($id);
        
            // L'entity Manager retient l'article à supprimer
            $em->remove($articlesDelete);
            // puis le supprime de la BDD
            $em->flush();

            // Message flash si l'article a bien été supprimé de la BDD
            $this->addFlash('delete', 'L \'article a bien été supprimé !');
            return $this->redirectToRoute('articles_list');

        }else{
            // Message flash si echec de la suppression
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_admin');
        }
    }
   
    // Fonction permettant d'afficher la liste des articles
    #[Route("/articles/list", name: "list")]
    public function articlesList(ArticlesRepository $articlesRepository): Response
    {
        // Find all récupère tous les articles
        return $this->render('admin/gestionStock/articles/list.html.twig', [
            'articlesList' => $articlesRepository->findAll()
        ]);
    }
}