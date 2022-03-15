<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Form\EditProfileType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/users", name="app_")
*/
class UsersController extends AbstractController
{
    // fonction permettant d'afficher la page profil de l'utilisateur
    /**
    * @Route("/", name="profil")
    */
    public function profil()
    {
        return $this->render('users/profil.html.twig');
    }

    // fonction permettant de modifier les infos de l'utilisateur
    /**
    * @Route("/profil/modifier/{id}", name="profil_change")
    */
    public function editProfil($id, Request $request, EntityManagerInterface $em)
    {
        // si le token est valide
        if($this->isCsrfTokenValid('update'.$id, $request->query->get('csrf')))
        {
            // recup le user déjà connecté
            $user = $this->getUser();

            // creation et gestion du form 
            $editProfile_form = $this->createForm(EditProfileType::class, $user);
            $editProfile_form->handleRequest($request);

            if($editProfile_form->isSubmitted() && $editProfile_form->isValid())
            {
                // indiquer a EM que cette entity devra etre enregistrer 
                $em->persist($user);
                // enregristrement de l'entity dans la BDD
                $em->flush();

                // si la modification a bien été effectué un message flash sera affiché sur la page app_profil
                $this->addFlash('update', 'Profil mis à jour');
                return $this->redirectToRoute('app_profil');
            }
        }else{
            // si la modif a échoué alors un message flash sera affiché sur la page app_profil
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('app_profil');
        } 

        return $this->render('users/profilChange.html.twig', [
            'editProfilForm' => $editProfile_form->createView()
        ]);
    }

    /**
    * @Route("/pass/modifier/{id}", name="pass_change")
    */
    public function editPass($id, Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $em)
    {
        // si le token est valide
        if($this->isCsrfTokenValid('update'.$id, $request->query->get('csrf')))
        {
            // recup le user déjà connecté
            $user = $this->getUser('id');

            // creation et gestion du form 
            $passEdit_form = $this->createForm(ChangePasswordFormType::class);
            $passEdit_form->handleRequest($request);

            if($passEdit_form->isSubmitted() && $passEdit_form->isValid())
            {
                // je recupere le MDP clair et le hash 
                $encodedPassword = $userPasswordHasherInterface->hashPassword(
                    $user,
                    $passEdit_form->get('plainPassword')->getData()
                );
                // j'affecte le MDP hashé 
                $user->setPassword($encodedPassword);
                // l'enregistrement du MDP hashé dans la BDD
                $em->flush();

                $this->addFlash('update', 'Votre mot de passe a bien été modifié !');
                return $this->redirectToRoute('app_profil');
            }
        }else{
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('app_profil');
        } 

        return $this->render('users/passChange.html.twig',[
            'editPassForm' => $passEdit_form->createView()
        ]);
    }

   
    // fonction permettant de supprimer le compte de l'utilisateur
    /**
    * @Route("/delete/{id}", name="pass_delete")
    */
    public function deletePass(EntityManagerInterface $em, UsersRepository $users, $id, Request $request)
    {
        if($this->isCsrfTokenValid('delete'.$id, $request->query->get('csrf')))
        {
            // recup le user déjà connecté
            $user = $users->find($id);
            // $session = $this->get('session');
            
            //j'instancie un nouvel objet Session
            $session = new Session();       
            
            // Invalidate permet de fermer la session utilisateur
            $session->invalidate();

            // Remove permet d'indiquer la suppression de l'utilisateur 
            $em->remove($user);
            // flush supprime le user 
            $em->flush();
            
            return $this->redirectToRoute('app_home');
        }else{
            // si la suppression n'a pas pu s'effectuer un message d'erreur affichera sur la page app_home  
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('app_home');
        }
    }
}