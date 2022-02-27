<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/admin", name="admin_")
*/
class UsersController extends AbstractController
{
    #[Route('/users/options', name: 'users_options')]
    public function users(): Response
    {
        return $this->render('admin/users/index.html.twig');
    }

    #[Route('/users/list', name: 'users_list')]
    public function users_list(UsersRepository $users, Request $request, PaginatorInterface $paginatorInterface ): Response
    {
        return $this->render('admin/users/list.html.twig', [
            'users' => $users->findAll()
        ]);
    }

    #[Route("/user/modifier/{id}", name: "modifier_user")]
    public function userModifier($id , Request $request, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid('update'.$id, $request->query->get('csrf')))
        {
            $userModifier = $this->getDoctrine()->getRepository(Users::class)->find($id);
            
            $userModifier_form = $this->createForm(UsersType::class, $userModifier);
            $userModifier_form->handleRequest($request);

            if($userModifier_form->isSubmitted() && $userModifier_form->isValid())
            {
                $em->persist($userModifier);
                $em->flush();

                $this->addFlash('update', 'Les information de l\'utilisateur ont bien été modifié !');
                return $this->redirectToRoute('admin_users_list');
            }
        }else{
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('admin/users/modifier.html.twig', [
            'userModifier' => $userModifier_form->createView()
        ]);
    }

    #[Route('/user/delete/all', name: 'delete_user_all')]
    public function usersDeleteAll(UsersRepository $usersRepository, EntityManagerInterface $em, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$this->getUser()->getId(), $request->query->get('csrf'))){
            $user = $usersRepository->unVerified();
            foreach($user as $value){
                $em->remove($value);
                $em->flush();
            }
            $this->addFlash('success', 'Les utilisateurs ont bien été supprimé !');
        }else{
            
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_users_list');
        }
        return $this->redirectToRoute('admin_users_list');
    }
    
    #[Route('/user/delete/{id}', name: 'delete_user')]
    public function usersDelete($id, EntityManagerInterface $em, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$id, $request->query->get('csrf'))) {
            $userDelete = $this->getDoctrine()->getRepository(Users::class)->find($id);
                $em->remove($userDelete);
                $em->flush();
            $this->addFlash('success', 'L\'utilisateur a bien été supprimé !');
        }else{
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('admin_users_list');
        }
        return $this->redirectToRoute('admin_users_list');
    }
}