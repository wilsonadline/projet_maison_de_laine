<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/admin", name="articles_")
*/
class ArticlesController extends AbstractController
{
    #[Route('/articles', name: 'options')]
    public function articles(): Response
    {
        return $this->render('admin/gestionStock/articles/index.html.twig');
    }

    #[Route("/articles/ajout", name: "ajout")]
    public function articlesAjout(Request $request, EntityManagerInterface $em): Response
    {
        $articleAjout = new Articles();

        $articlesAjout_form = $this->createForm(ArticlesType::class, $articleAjout);
        $articlesAjout_form->handleRequest($request);

        if($articlesAjout_form->isSubmitted() && $articlesAjout_form->isValid())
        {
            $articleAjout->setCreatedAt(new \DateTime());

            $em->persist($articleAjout);
            $em->flush();

            $this->addFlash('add', 'L\'article a bien été ajouté !');
            return $this->redirectToRoute('articles_list');
        }

        return $this->render('admin/gestionStock/articles/ajout.html.twig', [
            'articlesAjout' => $articlesAjout_form->createView()
        ]);
    }

    #[Route("/articles/modifier/{id}", name: "modifier")]
    public function articlesModifier($id , Request $request, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid('update'.$id, $request->query->get('csrf')))
        {
            $articlesModifier = $this->getDoctrine()->getRepository(Articles::class)->find($id);

            $articlesModifier_form = $this->createForm(ArticlesType::class, $articlesModifier);
            $articlesModifier_form->handleRequest($request);

            if($articlesModifier_form->isSubmitted() && $articlesModifier_form->isValid())
            {
                $articlesModifier->setUpdatedAt(new \DateTime());

                $em->persist($articlesModifier);
                $em->flush();

                $this->addFlash('update', 'L\'article a bien été modifié !');
                return $this->redirectToRoute('articles_list');
            }
        }else{
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_admin');
        }

        return $this->render('admin/gestionStock/articles/modifier.html.twig', [
            'articlesModifier' => $articlesModifier_form->createView()
        ]);
    }

    #[Route("/articles/delete/{id}", name: "delete")]
    public function articlesDelete($id, EntityManagerInterface $em, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$id, $request->query->get('csrf')))
        {
            $articlesDelete = $this->getDoctrine()->getRepository(Articles::class)->find($id);
        
            $em->remove($articlesDelete);
            $em->flush();

            $this->addFlash('delete', 'L \'article a bien été supprimé !');
        }else{
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_admin');
        }

        return $this->redirectToRoute('articles_list');
    }
   
    #[Route("/articles/list", name: "list")]
    public function articlesList(ArticlesRepository $articlesList): Response
    {
        return $this->render('admin/gestionStock/articles/list.html.twig', [
            'articlesList' => $articlesList->findAll()
        ]);
    }
}