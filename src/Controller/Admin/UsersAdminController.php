<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Préfixe pour toutes les fonctions du controller Users gerer par l'admin
/**
* @Route("/admin", name="admin_")
*/
class UsersAdminController extends AbstractController
{
    // Création du chemin vers la page index des users - admin
    #[Route('/users/options', name: 'users_options')]
    public function users_admin(): Response
    {
        return $this->render('admin/users/index.html.twig');
    }

    // Fonction permettant d'afficher la liste des users ayant un compte
    #[Route('/users/list', name: 'users_list')]
    public function users_list(UsersRepository $users): Response
    {
        // Find all récupère tous les users
        return $this->render('admin/users/list.html.twig', [
            'users' => $users->findAll()
        ]);
    }

    // Fonction permettant de modifier les infos des users 
    #[Route("/user/modifier/{id}", name: "modifier_user")]
    public function userModifier($id, Request $request, EntityManagerInterface $em, UsersRepository $usersRepository): Response
    {
        // Si le Csrf token est valide
        if($this->isCsrfTokenValid('update'.$id, $request->query->get('csrf')))
        {
            // recupération de l'id 
            $userModifier = $usersRepository->find($id);
            
            // Doctrine crée un form
            $userModifier_form = $this->createForm(UsersType::class, $userModifier);
            // traitement de la saisie du form
            $userModifier_form->handleRequest($request);

            if($userModifier_form->isSubmitted() && $userModifier_form->isValid())
            {
                // indiquer a EM que cette entity devra etre enregistrer
                $em->persist($userModifier);
                // enregristrement de l'entity dans la BDD
                $em->flush();

                // si toutes ces étapes sont validées, affichage d'un message flash de l'update
                $this->addFlash('update', 'Les information de l\'utilisateur ont bien été modifié !');
                return $this->redirectToRoute('admin_users_list');
            }
        }else{
            // si non un message flash de l'error sera affiché
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_users_list');
        }

        // création de la view du form affiché sur la page indiqué au render
        return $this->render('admin/users/modifier.html.twig', [
            'userModifier' => $userModifier_form->createView()
        ]);
    }

    // Fonction permettant de supprimer tous les users dont l'email n'est pas vérifié
    #[Route('/user/delete/all', name: 'delete_users_all')]
    public function usersDeleteAll(UsersRepository $usersRepository, EntityManagerInterface $em, Request $request): Response
    {
        // Si le Csrf token est valide
        if($this->isCsrfTokenValid('delete'.$this->getUser()->getId(), $request->query->get('csrf')))
        {
            // j'appelle ma fonctione unVerified se trouvant dans le UsersRepository
            $users = $usersRepository->unVerified();

            // boucle supprimant les users dont l'email n'est pas vérifié
            foreach($users as $value){
                $em->remove($value);
                $em->flush();
            }

            // Message flash s'ils ont bien été supprimé de la BDD
            $this->addFlash('success', 'Les utilisateurs ont bien été supprimé !');
            return $this->redirectToRoute('admin_users_list');
        }else{
            // Message flash si echec de la suppression
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_users_list');
        }
    }

    // Fonction permettant de supprimer un user
    #[Route('/user/delete/{id}', name: 'delete_user')]
    public function usersDelete($id, EntityManagerInterface $em, Request $request, UsersRepository $usersRepository): Response
    {
        // Si le Csrf token est valide
        if($this->isCsrfTokenValid('delete'.$id, $request->query->get('csrf'))) 
        {
            // recupération de l'id 
            $userDelete = $usersRepository->find($id);

            // L'entity Manager retient l'utilisateur à supprimer
            $em->remove($userDelete);
            // puis le supprime de la BDD
            $em->flush();
            
            // Message flash si l'utilisateur a bien été supprimé de la BDD
            $this->addFlash('success', 'L\'utilisateur a bien été supprimé !');
            return $this->redirectToRoute('admin_users_list');
        }else{
            // Message flash si echec de la suppression
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_users_list');
        }
    }
}