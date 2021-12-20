<?php

namespace App\Controller\Admin;

use App\Entity\TypeCategories;
use App\Form\TypeCategoriesType;
use App\Repository\TypeCategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="type_categories_")
 */
class TypeCategoriesController extends AbstractController
{
    #[Route("/typeCategories/ajout", name: "ajout")]
    public function typeCategoriesAjout(Request $request): Response
    {
        $typeCategoriesAjout = new TypeCategories();

        $typeCategoriesAjout_form = $this->createForm(TypeCategoriesType::class, $typeCategoriesAjout);
        $typeCategoriesAjout_form->handleRequest($request);

        if($typeCategoriesAjout_form->isSubmitted() && $typeCategoriesAjout_form->isValid())
        {
            $typeCategoriesAjout->setCreatedAt(new \DateTime());

            $em= $this->getDoctrine()->getManager();
            $em->persist($typeCategoriesAjout);
            $em->flush();

            $this->addFlash('typeCategoriesAdd', 'Le type de catégorie a bien été ajouté ! ');
            return $this->redirectToRoute('type_categories_list');
        }

        return $this->render('admin/type_categories/ajout.html.twig', [
            'typeCategoriesAjout' => $typeCategoriesAjout_form->createView(),
        ]);
    }

    #[Route("/typeCategories/modifier/{id}", name: "modifier")]
    public function typeCategoriesModifier($id , Request $request): Response
    {
        $typeCategoriesModifier = $this->getDoctrine()->getRepository(TypeCategories::class)->find($id);
        $typeCategoriesModifier_form = $this->createForm(TypeCategoriesType::class, $typeCategoriesModifier);
        $typeCategoriesModifier_form->handleRequest($request);

        if($typeCategoriesModifier_form->isSubmitted() && $typeCategoriesModifier_form->isValid())
        {
            $typeCategoriesModifier->setUpdatedAt(new \DateTime());

            $em= $this->getDoctrine()->getManager();
            $em->persist($typeCategoriesModifier);
            $em->flush();

            $this->addFlash('typeCategoriesEdit', 'Le type de catégorie a bien été modifié ! ');
            return $this->redirectToRoute('type_categories_list');
        }

        return $this->render('admin/type_categories/modifier.html.twig', [
            'typeCategoriesModifier' => $typeCategoriesModifier_form->createView(),
        ]);
    }

    #[Route("/typeCategories/delete/{id}", name: "delete")]
    public function typeCategoriesDelete($id): Response
    {
        $typeCategoriesDelete = $this->getDoctrine()->getRepository(TypeCategories::class)->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($typeCategoriesDelete);
        $em->flush();
        
        $this->addFlash('typeCategoriesDelete', 'Le type de catégorie a bien été supprimé ! ');
        return $this->redirectToRoute('type_categories_list');
    }

    #[Route("/typeCategories/list", name: "list")]
    public function typeCategoriesList(TypeCategoriesRepository $typeCategoriesList): Response
    {
        return $this->render('admin/type_categories/list.html.twig', [
            'typeCategoriesList' => $typeCategoriesList->findAll(),
        ]);
    }
}
