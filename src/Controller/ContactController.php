<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    // fonction Contact
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer, EntityManagerInterface $em): Response
    {
        // creation d'un nouvel objet contact
        $contact = new Contact();
        // initialiser le sujet de l'objet contact à un string vide
        $contact->setSujet('');
        // j'appelle doctrine pour créer un form 
        $form_contact = $this->createForm(ContactType::class, $contact);
        // demande de traitement de la saisie du form
        $form_contact->handleRequest($request);
        
        // si le form est soumis et qu'il est valide
        if($form_contact->isSubmitted() && $form_contact->isValid())
        {
            // récupérer les informations saisies
            $em->persist($contact);
            // envoyer les informations à la BDD
            $em->flush();

            // Pour l'envoie de l'email, j'appelle ma fonction sendEmail et ses dependences
            $this->sendEmail($mailer, $contact);
            // un message flash s'affichera à l'envoie de l'email
            $this->addFlash('add', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('contact');
        }
        
        // création de la view du form affiché sur la page indiqué au render
        return $this->render('contact/index.html.twig', [
            'formContact' => $form_contact->createView()
        ]);
    }

    // fontion pour l'envoi de l'email
    private function sendEmail(MailerInterface $mailer, Contact $contact)
    {
        // création d'un nouvel objet email
        $email = (new Email())
            // la fonction from permets de définir l'expéditeur, en l'occurence l'email contact du site web
            ->from("contact@wilson-a-portefolio.com")
            // la fonction from permets de définir le destinataire
            ->to('maisondelaine.dwwm9@gmail.com')
            // l'objet de l'email renseigner par l'utilisateur dans le form contact
            ->subject($contact->getSujet())
            // le corps du message contruit à partir de la vue email/contact
            // et des données renseignées par l'utilisateur sur le form contact.
            ->html($this->renderView(
                'emails/contact.html.twig',
                ['contact'=> $contact])
            );
        // une fois l'email contruit, le Mailerinterface envoie l'email, grâce a sa fonction send.
        $mailer->send($email);
    }
}