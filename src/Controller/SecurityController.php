<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ChangePasswordFormType;
use App\Form\EditProfileType;
use App\Form\PassEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }


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
     * @Route("/users/pass/modifier", name="app_pass_change")
     */
    public function editPass(Request $request , EntityManagerInterface $em)
    {

            $user = $this->getUser();

            $passEdit_form = $this->createForm(ChangePasswordFormType::class);
            $passEdit_form->handleRequest($request);
            // dd($passEdit_form);
            if($passEdit_form->isSubmitted() && $passEdit_form->isValid()){
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('app_profil');
            }
           
        return $this->render('security/passChange.html.twig',[
            'editPassForm' => $passEdit_form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
