<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;



class BaseController extends AbstractController{

    /**
     * @Route("/base" , name="base")
     */
    public function log(UsersRepository $user){
        $user = $user->findBy(['id' => $user]);

        return $this->render('base.html.twig', [
            'user' => $user
        ]);

    }
}
