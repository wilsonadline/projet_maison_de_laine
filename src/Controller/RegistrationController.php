<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $em): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if($user->setPassword(
                $userPasswordHasherInterface->hashPassword( 
                    $user,$form->get('password')->getData())
                )
            )
            {
                $user->setCreatedAt(new \DateTime());
                $user->setRoles(["ROLE_USER"]);
                $em->persist($user);
                $em->flush();
            }


            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('contact@wilson-a-portefolio.com', 'Maison De Laine'))
                    ->to($user->getEmail())
                    ->subject('Veuillez confirmer votre email svp')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            $this->addFlash('info', "Votre compte a bien été crée avec succès. D'autres instructions ont été envoyées à votre adresse e-mail ");

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try{
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        }catch(VerifyEmailExceptionInterface $exception){
            $this->addFlash('error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre email adresse a bien été vérifié');

        return $this->redirectToRoute('app_home');
    }
}