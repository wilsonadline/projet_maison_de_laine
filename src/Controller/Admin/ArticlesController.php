<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="articles_")
 */
class ArticlesController extends AbstractController
{
    #[Route("/articles/ajout", name: "ajout")]
    public function articlesAjout(Request $request): Response
    {
        $articlesAjout = new Articles();

        $articlesAjout_form = $this->createForm(ArticlesType::class, $articlesAjout);
        $articlesAjout_form->handleRequest($request);

        if($articlesAjout_form->isSubmitted() && $articlesAjout_form->isValid())
        {
            $articlesAjout->setCreatedAt(new \DateTime());

            $em= $this->getDoctrine()->getManager();
            $em->persist($articlesAjout);
            $em->flush();

            $this->addFlash('articlesAdd', 'L \'article a bien été ajouté ! ');
            return $this->redirectToRoute('articles_list');
        }

        return $this->render('admin/articles/ajout.html.twig', [
            'articlesAjout' => $articlesAjout_form->createView(),
        ]);
    }

    #[Route("/articles/modifier/{id}", name: "modifier")]
    public function articlesModifier($id , Request $request): Response
    {
        $articlesModifier = $this->getDoctrine()->getRepository(Articles::class)->find($id);

        $articlesModifier_form = $this->createForm(ArticlesType::class, $articlesModifier);
        $articlesModifier_form->handleRequest($request);

        if($articlesModifier_form->isSubmitted() && $articlesModifier_form->isValid())
        {
            $articlesModifier->setUpdatedAt(new \DateTime());

            $em= $this->getDoctrine()->getManager();
            $em->persist($articlesModifier);
            $em->flush();

            $this->addFlash('articlesEdit', 'L \'article a bien été modifié ! ');
            return $this->redirectToRoute('articles_list');
        }

        return $this->render('admin/articles/modifier.html.twig', [
            'articlesModifier' => $articlesModifier_form->createView(),
        ]);
    }

    #[Route("/articles/delete/{id}", name: "delete")]
    public function articlesDelete($id): Response
    {

        $articlesDelete = $this->getDoctrine()->getRepository(Articles::class)->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($articlesDelete);
        $em->flush();
        
        $this->addFlash('articlesDelete', 'L \'article a bien été supprimé ! ');
        return $this->redirectToRoute('articles_list');
    }

    #[Route("/articles/list", name: "list")]
    public function articlesList(ArticlesRepository $articlesList): Response
    {
        return $this->render('admin/articles/list.html.twig', [
            'articlesList' => $articlesList->findAll(),
        ]);
    }
}
