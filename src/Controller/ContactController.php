<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request,  \Swift_Mailer $mailer): Response
    {
        $contact = new Contact();
        $contact->setSujet('');
        $form_contact = $this->createForm(ContactType::class, $contact);
        $form_contact->handleRequest($request);
        
        if($form_contact->isSubmitted() && $form_contact->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();
            
            $message = (new \Swift_Message('Mail de Contact'))
            ->setFrom($contact->getEmail())
            ->setTo('adminMDL@gmail.com')
            ->setBody(
                $this->renderView(
                    'emails/contact.html.twig',
                    ['contact' => $contact]
                ),
                'text/html'
            );
            $mailer->send($message);
            $this->addFlash('contact_sent', 'Votre message a bien été envoyé');
        }

        return $this->render('contact/index.html.twig', [
            'formContact' => $form_contact->createView()
        ]);
    }
}
