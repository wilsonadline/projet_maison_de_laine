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
    public function delivery_options_update(DelivryRepository $delivryRepository, $id, Request $request, EntityManagerInterface $em): Response
    {
        $update_delivery_form = $this->createForm(DeliveryType::class , $delivryRepository->find($id));
        $update_delivery_form->handleRequest($request);

        if($update_delivery_form->isSubmitted() && $update_delivery_form->isValid()){
            $em->persist($delivryRepository->find($id));
            $em->flush();  
            
            $this->addFlash('delivery_option_update', 'L\'option a bien été modifié !');
            return $this->redirectToRoute('delivery_options_list');
        }
        return $this->render('admin/delivery/modifier.html.twig', [
            'update_delivery' => $update_delivery_form->createView()
        ]);
    } 
    
    #[Route('/delivery/supprimer/{id}', name: 'delivery_options_delete')]    
    public function delivery_options_delete($id, EntityManagerInterface $em, DelivryRepository $delivryRepository): Response
    {
        $em->remove($delivryRepository->find($id));
        $em->flush();

        $this->addFlash('delivery_option_delete', 'L\'option a bien été supprimé !');
        return $this->redirectToRoute('delivery_options_list');
    }
}
