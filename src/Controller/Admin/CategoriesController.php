<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/admin", name="categories_")
*/
class CategoriesController extends AbstractController
{
    #[Route('/categories', name: 'options')]
    public function categories(): Response
    {
        return $this->render('admin/gestionStock/categories/index.html.twig');
    }

    #[Route("/categories/ajout", name: "ajout")]
    public function categoriesAjout(Request $request, EntityManagerInterface $em): Response
    {
        $categoriesAjout = new Categories();
      
        $categoriesAjout_form = $this->createForm(CategoriesType::class, $categoriesAjout);
        $categoriesAjout_form->handleRequest($request);

        if($categoriesAjout_form->isSubmitted() && $categoriesAjout_form->isValid())
        {
            $categoriesAjout->setCreatedAt(new \DateTime());
            
            $em->persist($categoriesAjout);
            $em->flush();
            
            $this->addFlash('add', 'La catégorie a bien été ajouté !');
            return $this->redirectToRoute('categories_list');
        }

        return $this->render('admin/gestionStock/categories/ajout.html.twig', [
            'categoriesAjout' => $categoriesAjout_form->createView()
        ]);
    }

    #[Route("/categories/modifier/{id}", name: "modifier")]
    public function categoriesModifier($id, Request $request, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid('update'.$id, $request->query->get('csrf')))
        {
            $categoriesModifier = $this->getDoctrine()->getRepository(Categories::class)->find($id);

            $categoriesModifier_form = $this->createForm(CategoriesType::class, $categoriesModifier);
            $categoriesModifier_form->handleRequest($request);

            if($categoriesModifier_form->isSubmitted() && $categoriesModifier_form->isValid())
            {
                $categoriesModifier->setUpdatedAt(new \DateTime());

                $em->persist($categoriesModifier);
                $em->flush();

                $this->addFlash('update', 'La catégorie a bien été modifié !');
                return $this->redirectToRoute('categories_list');
            }
        }else{
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_admin');
        }

        return $this->render('admin/gestionStock/categories/modifier.html.twig', [
            'categoriesModifier' => $categoriesModifier_form->createView()
        ]);
    }

    #[Route("/categories/delete/{id}", name: "delete")]
    public function categoriesDelete($id, EntityManagerInterface $em, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$id, $request->query->get('csrf')))
        {
           $categoriesDelete = $this->getDoctrine()->getRepository(Categories::class)->find($id);

            $em->remove($categoriesDelete);
            $em->flush();

            $this->addFlash('delete', 'La catégorie a bien été supprimé !');
            return $this->redirectToRoute('categories_list');
        }else{
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_admin');
        }
    }

    #[Route("/categories/list", name: "list")]
    public function categoriesList(CategoriesRepository $categoriesList): Response
    {
        return $this->render('admin/gestionStock/categories/list.html.twig', [
            'categoriesList' => $categoriesList->findAll()
        ]);
    }
}