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

/**
* @Route("/admin", name="delivery_")
*/
class DeliveryController extends AbstractController
{
    #[Route('/delivery', name: 'options')]
    public function delivery(): Response
    {
        return $this->render('admin/delivery/index.html.twig');
    }

    #[Route('/delivery/list', name: 'options_list')]
    public function delivery_options_list(DelivryRepository $delivryRepository): Response
    {
        return $this->render('admin/delivery/list.html.twig', [
            'delivery_list' => $delivryRepository->findAll()
        ]);
    }

    #[Route('/delivery/ajouter', name: 'options_add')]
    public function delivery_options_add(Request $request, EntityManagerInterface $em): Response
    {
        $add_delivery = new Delivry();

        $add_delivery_form = $this->createForm(DeliveryType::class , $add_delivery);
        $add_delivery_form->handleRequest($request);

        if($add_delivery_form->isSubmitted()&& $add_delivery_form->isValid()){
            $em->persist($add_delivery);
            $em->flush();

            $this->addFlash('add', 'L\'option a bien été ajouté !');
            return $this->redirectToRoute('delivery_options_list');
        }

        return $this->render('admin/delivery/ajout.html.twig', [
            'add_delivery' => $add_delivery_form->createView()
        ]);
    } 
    
    #[Route('/delivery/modifier/{id}', name: 'options_update')]
    public function delivery_options_update(DelivryRepository $delivryRepository, $id, Request $request, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid('update'.$id, $request->query->get('csrf')))
        {     
            $update_delivery_form = $this->createForm(DeliveryType::class , $delivryRepository->find($id));
            $update_delivery_form->handleRequest($request);

            if($update_delivery_form->isSubmitted() && $update_delivery_form->isValid()){
                $em->persist($delivryRepository->find($id));
                $em->flush();
            
                $this->addFlash('update', 'L\'option a bien été modifié !');
                return $this->redirectToRoute('delivery_options_list');
            }
        }else{
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_admin');
        }

        return $this->render('admin/delivery/modifier.html.twig', [
            'update_delivery' => $update_delivery_form->createView()
        ]);
    } 
    
    #[Route('/delivery/supprimer/{id}', name: 'options_delete')]    
    public function delivery_options_delete($id, EntityManagerInterface $em, DelivryRepository $delivryRepository, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$id, $request->query->get('csrf')))
        {
            $em->remove($delivryRepository->find($id));
            $em->flush();

            $this->addFlash('delete', 'L\'option a bien été supprimé !');
            return $this->redirectToRoute('delivery_options_list');
        }else{
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_admin');
        }
    }
}