<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer, EntityManagerInterface $em): Response
    {
        $contact = new Contact();
        $contact->setSujet('');
        $form_contact = $this->createForm(ContactType::class, $contact);
        $form_contact->handleRequest($request);
        
        if($form_contact->isSubmitted() && $form_contact->isValid())
        {
            $em->persist($contact);
            $em->flush();

            $this->sendEmail($mailer, $contact);
            
            // $message = (new \Swift_Message('Mail de Contact'))
            // ->setFrom($contact->getEmail())
            // ->setTo('maisondelaine.dwwm9@gmail.com')
            // ->setBody(
            //     $this->renderView(
            //         'emails/contact.html.twig',
            //         ['contact'=> $contact]
            //     ),
            //     'text/html'
            // );
            // $mailer->send($message);
            $this->addFlash('contact_sent', 'Votre message a bien été envoyé');
        }

        return $this->render('contact/index.html.twig', [
            'formContact' => $form_contact->createView()
        ]);
    }

   
    private function sendEmail(MailerInterface $mailer, Contact $contact)
    {
        $email = (new Email())
            ->from($contact->getEmail())
            ->to('maisondelaine.dwwm9@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($contact->getSujet())
            // ->text('Sending emails is fun again!')
            ->html($this->renderView(
                        'emails/contact.html.twig',
                        ['contact'=> $contact])
                    );

        $mailer->send($email);

        // ...
    }
}
