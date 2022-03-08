<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class UsersController extends AbstractController
{
    /**
    * @Route("/users", name="app_profil")
    */
    public function profil()
    {
        return $this->render('users/profil.html.twig');
    }

    /**
    * @Route("/users/profil/modifier/{id}", name="app_profil_change")
    */
    public function editProfil($id, Request $request, EntityManagerInterface $em)
    {
        if($this->isCsrfTokenValid('update'.$id, $request->query->get('csrf')))
        {
            $user = $this->getUser();

            $editProfile_form = $this->createForm(EditProfileType::class, $user);
            $editProfile_form->handleRequest($request);

            if($editProfile_form->isSubmitted() && $editProfile_form->isValid())
            {
                $em->persist($user);
                $em->flush();

                $this->addFlash('update', 'Profil mis à jour');
                return $this->redirectToRoute('app_profil');
            }
        }else{
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('app_home');
        } 

        return $this->render('users/profilChange.html.twig', [
            'editProfilForm' => $editProfile_form->createView()
        ]);
    }

    /**
    * @Route("/users/pass/modifier/{id}", name="app_pass_change")
    */
    public function editPass($id, Request $request, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        if($this->isCsrfTokenValid('update'.$id, $request->query->get('csrf')))
        {
            $user = $this->getUser('id');

            $passEdit_form = $this->createForm(ChangePasswordFormType::class);
            $passEdit_form->handleRequest($request);

            if($passEdit_form->isSubmitted() && $passEdit_form->isValid())
            {
                $encodedPassword = $userPasswordHasherInterface->hashPassword(
                    $user,
                    $passEdit_form->get('plainPassword')->getData()
                );

                $user->setPassword($encodedPassword);
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('update', 'Votre mot de passe a bien été modifié !');
                return $this->redirectToRoute('app_profil');
            }
        }else{
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('app_home');
        } 

        return $this->render('users/passChange.html.twig',[
            'editPassForm' => $passEdit_form->createView()
        ]);
    }

    /**
    * @Route("/users/delete/{id}", name="app_pass_delete")
    */
    public function deletePass(EntityManagerInterface $em, UsersRepository $users, $id, Request $request)
    {
        if($this->isCsrfTokenValid('delete'.$id, $request->query->get('csrf')))
        {
            $user = $users->find($id);
            $session = $this->get('session');
            $session = new Session();
            $session->invalidate();
            $em->remove($user);
            $em->flush();
            return $this->redirectToRoute('app_home');
            $this->addFlash('delete', 'Votre profil a bien été supprimé !');
        }else{
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('app_home');
        }
    }
}