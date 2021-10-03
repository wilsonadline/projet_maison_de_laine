<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin", name="categories_")
 */
class CategoriesController extends AbstractController
{
    #[Route("/categories/ajout", name: "ajout")]
    public function categoriesAjout(Request $request): Response
    {
        $categoriesAjout = new Categories();

        $categoriesAjout_form = $this->createForm(CategoriesType::class, $categoriesAjout);
        $categoriesAjout_form->handleRequest($request);

        if($categoriesAjout_form->isSubmitted() && $categoriesAjout_form->isValid())
        {
            $categoriesAjout->setCreatedAt(new \DateTime());

            $em= $this->getDoctrine()->getManager();
            $em->persist($categoriesAjout);
            $em->flush();

            $this->addFlash('categoriesAdd', 'La catégorie a bien été ajouté ! ');
            return $this->redirectToRoute('categories_list');
        }

        return $this->render('admin/categories/ajout.html.twig', [
            'categoriesAjout' => $categoriesAjout_form->createView(),
        ]);
    }

    #[Route("/categories/modifier/{id}", name: "modifier")]
    public function categoriesModifier($id , Request $request): Response
    {
        $categoriesModifier = $this->getDoctrine()->getRepository(Categories::class)->find($id);

        $categoriesModifier_form = $this->createForm(CategoriesType::class, $categoriesModifier);
        $categoriesModifier_form->handleRequest($request);

        if($categoriesModifier_form->isSubmitted() && $categoriesModifier_form->isValid())
        {
            $categoriesModifier->setUpdatedAt(new \DateTime());

            $em= $this->getDoctrine()->getManager();
            $em->persist($categoriesModifier);
            $em->flush();

            $this->addFlash('categoriesEdit', 'La catégorie a bien été modifié ! ');
            return $this->redirectToRoute('categories_list');
        }

        return $this->render('admin/categories/modifier.html.twig', [
            'categoriesModifier' => $categoriesModifier_form->createView(),
        ]);
    }

    #[Route("/categories/delete/{id}", name: "delete")]
    public function categoriesDelete($id): Response
    {

        $categoriesDelete = $this->getDoctrine()->getRepository(Categories::class)->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($categoriesDelete);
        $em->flush();
        
        $this->addFlash('categoriesDelete', 'La catégorie a bien été supprimé ! ');
        return $this->redirectToRoute('categories_list');
    }

    #[Route("/categories/list", name: "list")]
    public function categoriesList(CategoriesRepository $categoriesList): Response
    {
        return $this->render('admin/categories/list.html.twig', [
            'categoriesList' => $categoriesList->findAll(),
        ]);
    }
}