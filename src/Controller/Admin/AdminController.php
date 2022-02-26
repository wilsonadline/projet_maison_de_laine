<?php

namespace App\Controller\Admin;

use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
    #[Route('users', name: 'users')]
    public function users(UsersRepository $usersRepository, EntityManagerInterface $em, HttpFoundationRequest $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$this->getUser()->getId(), $request->query->get('csrf'))) {
            $user = $usersRepository->unVerified();
            foreach($user as $value){
                $em->remove($value);
                $em->flush();
            }
        }else{
            dd("ahhhh");
        }
        return $this->redirectToRoute('app_home');
    }

}