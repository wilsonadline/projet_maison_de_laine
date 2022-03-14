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

    // Fontion pour s'inscrire
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $em): Response
    {
        // j'instancie un nouvel objet utilisateur
        $user = new Users();
        
        // création du form 
        $form = $this->createForm(RegistrationFormType::class, $user);
        // demande de traitement de la saisie du form
        $form->handleRequest($request);
        
        // si le form n'est pas envoyer ou s'il n'est pas valide
        if(!$form->isSubmitted() || !$form->isValid())
        {
            // afficher la vue du form de creation vide 
            return $this->render('registration/register.html.twig', [
                'registrationForm' => $form->createView()
            ]);

        }

        // j'affecte le MDP de l'utilisateur après l'avoir hashé 
        $user->setPassword(
            $userPasswordHasherInterface->hashPassword(
                $user, 
                $form->get('password')->getData()
            )
        );
  
        // alors une heure de création et le role seront établis
        $user->setCreatedAt(new \DateTime());
        $user->setRoles(["ROLE_USER"]);
        
        // indiquer a EM que cette entity devra etre enregistrer 
        $em->persist($user);
        // enregristrement de l'entity dans la BDD
        $em->flush();

        // envoie d'un email au user pour confirmer que l'email qu'il a saisie est bien valide
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('contact@wilson-a-portefolio.com', 'Maison De Laine'))
                ->to($user->getEmail())
                ->subject('Veuillez confirmer votre email svp')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );

        $this->addFlash('info', "Votre compte a bien été crée avec succès. D'autres instructions ont été envoyées à votre adresse e-mail.");

        return $this->redirectToRoute('app_home');

    }

    // fonction permettant de vérifier si le user possede bien l'email  indiquer
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        // si le user n'est pas pleinement authentifier, il sera rediriger vers la page de login
        // si le user s'est bien authentifier alors il revient automatiquement sur cette focntion du controller pour poursuivre la confirmation de l'email
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try{
            // valider le lien de confirmation de l'email, et établir User::isVerified à true et persister
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        }catch(VerifyEmailExceptionInterface $exception){
            // si la validation a echoué , le user sera redirigé vers app_register où un message flash sera affiché indiquant la raison de l'echec.
            $this->addFlash('error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }
        //si la validation a réussi , le user sera redirigé vers app_home où un message flash sera affiché indiquant le succes de la validation de son adresse email.
        $this->addFlash('success', 'Votre email adresse a bien été vérifié');

        return $this->redirectToRoute('app_home');
    }
}