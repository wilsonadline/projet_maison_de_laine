<?php

namespace App\Controller\Admin;

use App\Entity\TypeCategories;
use App\Form\TypeCategoriesType;
use App\Repository\TypeCategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/admin", name="type_categories_")
*/
class TypeCategoriesController extends AbstractController
{
    #[Route('/typeCategories', name: 'options')]
    public function typeCategories(): Response
    {
        return $this->render('admin/gestionStock/type_categories/index.html.twig');
    }
    
    #[Route("/typeCategories/ajout", name: "ajout")]
    public function typeCategoriesAjout(Request $request, EntityManagerInterface $em): Response
    {
        $typeCategoriesAjout = new TypeCategories();

        $typeCategoriesAjout_form = $this->createForm(TypeCategoriesType::class, $typeCategoriesAjout);
        $typeCategoriesAjout_form->handleRequest($request);

        if($typeCategoriesAjout_form->isSubmitted() && $typeCategoriesAjout_form->isValid())
        {
            $typeCategoriesAjout->setCreatedAt(new \DateTime());

            $em->persist($typeCategoriesAjout);
            $em->flush();

            $this->addFlash('add', 'Le type de catégorie a bien été ajouté !');
            return $this->redirectToRoute('type_categories_list');
        }

        return $this->render('admin/gestionStock/type_categories/ajout.html.twig', [
            'typeCategoriesAjout' => $typeCategoriesAjout_form->createView()
        ]);
    }

    #[Route("/typeCategories/modifier/{id}", name: "modifier")]
    public function typeCategoriesModifier($id, Request $request, EntityManagerInterface $em): Response
    {
        $typeCategoriesModifier = $this->getDoctrine()->getRepository(TypeCategories::class)->find($id);
        $typeCategoriesModifier_form = $this->createForm(TypeCategoriesType::class, $typeCategoriesModifier);
        $typeCategoriesModifier_form->handleRequest($request);

        if($typeCategoriesModifier_form->isSubmitted() && $typeCategoriesModifier_form->isValid())
        {
            $typeCategoriesModifier->setUpdatedAt(new \DateTime());

            $em->persist($typeCategoriesModifier);
            $em->flush();

            $this->addFlash('update', 'Le type de catégorie a bien été modifié !');
            return $this->redirectToRoute('type_categories_list');
        }

        return $this->render('admin/gestionStock/type_categories/modifier.html.twig', [
            'typeCategoriesModifier' => $typeCategoriesModifier_form->createView()
        ]);
    }

    #[Route("/typeCategories/delete/{id}", name: "delete")]
    public function typeCategoriesDelete($id, EntityManagerInterface $em): Response
    {
        $typeCategoriesDelete = $this->getDoctrine()->getRepository(TypeCategories::class)->find($id);

        $em->remove($typeCategoriesDelete);
        $em->flush();
        
        $this->addFlash('delete', 'Le type de catégorie a bien été supprimé !');
        return $this->redirectToRoute('type_categories_list');
    }

    #[Route("/typeCategories/list", name: "list")]
    public function typeCategoriesList(TypeCategoriesRepository $typeCategoriesList): Response
    {
        return $this->render('admin/gestionStock/type_categories/list.html.twig', [
            'typeCategoriesList' => $typeCategoriesList->findAll()
        ]);
    }
}