<?php

namespace App\Controller\Admin;

use App\Entity\Delivry;
use App\Form\DeliveryType;
use App\Repository\DelivryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeliveryController extends AbstractController
{
    #[Route('/delivery', name: 'delivery_options')]
    public function delivery(): Response
    {
        return $this->render('delivery/index.html.twig', [
            'controller_name' => 'DeliveryController',
        ]);
    }

    #[Route('/delivery/list', name: 'delivery_options_list')]
    public function delivery_options_list(DelivryRepository $delivryRepository): Response
    {
        return $this->render('admin/delivery/list.html.twig', [
            'delivery_list' => $delivryRepository->findAll()
        ]);
    }

    #[Route('/delivery/ajouter', name: 'delivery_options_add')]
    public function delivery_options_add(Request $request, EntityManagerInterface $em): Response
    {
        $add_delivery = new Delivry();

        $add_delivery_form = $this->createForm(DeliveryType::class , $add_delivery);
        $add_delivery_form->handleRequest($request);

        if($add_delivery_form->isSubmitted()&& $add_delivery_form->isValid()){
            $em->persist($add_delivery);
            $em->flush();

            $this->addFlash('delivery_option_add', 'L\'option a bien été ajouté !');
            return $this->redirectToRoute('delivery_options_list');
        }

        return $this->render('admin/delivery/ajout.html.twig', [
            'add_delivery' => $add_delivery_form->createView()
        ]);
    } 
    
    #[Route('/delivery/modifier/{id}', name: 'delivery_options_update')]
    public function delivery_options_update(): Response
    {
        return $this->render('admin/delivery/delivery_update.html.twig', [
            'controller_name' => 'DeliveryController',
        ]);
    } 
    
    #[Route('/delivery/supprimer', name: 'delivery_options_delete')]
    public function delivery_options_delete(): Response
    {
        return $this->render('admin/delivery/delivery_delete.html.twig', [
            'controller_name' => 'DeliveryController',
        ]);
    }
}
