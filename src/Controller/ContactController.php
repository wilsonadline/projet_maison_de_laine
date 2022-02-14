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
            $this->addFlash('contact_sent', 'Votre message a bien été envoyé');

        }

        return $this->render('contact/index.html.twig', [
            'formContact' => $form_contact->createView()
        ]);
    }
   
    private function sendEmail(MailerInterface $mailer, Contact $contact)
    {
        $email = (new Email())
            ->from("contact@wilson-a-portefolio.com")
            ->to('maisondelaine.dwwm9@gmail.com')
            ->subject($contact->getSujet())
            ->html($this->renderView(
                        'emails/contact.html.twig',
                        ['contact'=> $contact])
                    );

        $mailer->send($email);
    }
}
