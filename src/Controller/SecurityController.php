<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Form\EditProfileType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/users", name="app_profil")
     */
    public function profil()
    {
        // $id = $users;
        // // dd($id);

        return $this->render('security/profil.html.twig');

    }

      /**
     * @Route("/users/profil/modifier", name="app_profil_change")
     */
    public function editProfil(Request $request, EntityManagerInterface $em)
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

        return $this->render('security/profilChange.html.twig', [
            'editProfilForm' => $editProfile_form->createView()
        ]);
    }

    /**
     * @Route("/users/pass/modifier/{id}", name="app_pass_change")
     */
    public function editPass(Request $request , UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $em, $id)
    {
        $user = $this->getUser('id');

        $passEdit_form = $this->createForm(ChangePasswordFormType::class);
        $passEdit_form->handleRequest($request);
        
        if($passEdit_form->isSubmitted() && $passEdit_form->isValid()){
            $encodedPassword = $userPasswordHasherInterface->hashPassword(
                $user,
                $passEdit_form->get('plainPassword')->getData());
                
            $user->setPassword($encodedPassword);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_profil');
        }
           
        return $this->render('security/passChange.html.twig',[
            'editPassForm' => $passEdit_form->createView()
        ]);
    }

     /**
     * @Route("/users/delete/{id}", name="app_pass_delete")
     */
    public function deletePass( EntityManagerInterface $em, UsersRepository $users, $id)
    {
        $user = $users->find($id);
                
        $session = $this->get('session');
        $session = new Session();
        $session->invalidate();
        $em->remove( $user);
        $em->flush();
        $this->addFlash('profilDelete', 'Votre profil a bien été supprimé ! ');
        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
