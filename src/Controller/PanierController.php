<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use App\Services\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/panier", name="panier_")
*/
class PanierController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function panier(SessionInterface $session, ArticlesRepository $articleRepository, EntityManagerInterface $em): Response
    {
        $paniers = new PanierService($em);
        list ($dataPanier, $total) = $paniers->panier($session, $articleRepository);

        return $this->render('panier/panier.html.twig', compact("dataPanier", "total")
        );
    }

    #[Route('/ajout/{id}', name: 'ajout')]
    public function panier_ajout( SessionInterface $session, Articles $article)
    {
        // on recupere le panier actuel
        $panier = $session->get("panier",  []);
        $id = $article->getId();
        
        if(!empty($panier[$id]))
        {
            $panier[$id]++;
        }
        else
        {
            $panier[$id] = 1;
        }
        
        // on sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("panier_index");
    }

    #[Route('/retirer/{id}', name: 'retirer')]
    public function panier_retirer( SessionInterface $session, Articles $article)
    {
        // on recupere le panier actuel
        $panier = $session->get("panier", []);
        $id = $article->getId();
        
        if(!empty($panier[$id]))
        {
            if($panier[$id] > 1)
            {
                $panier[$id]--;
            }
            else{
                unset($panier[$id]);
            }
        }
       
        // on sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("panier_index");
    }

    #[Route('/supprimer/{id}', name: 'supprimer')]
    public function panier_supprimer( SessionInterface $session, Articles $article)
    {
        // on recupere le panier actuel
        $panier = $session->get("panier", []);
        $id = $article->getId();
        
        if(!empty($panier[$id]))
        {
            unset($panier[$id]);
        }
       
        // on sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("panier_index");
    }

    #[Route('/vider', name: 'vider')]
    public function panier_vider( SessionInterface $session)
    {
        $session->remove("panier");

        return $this->redirectToRoute("panier_index");
    }
}
