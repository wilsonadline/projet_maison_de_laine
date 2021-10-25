<?php

namespace App\Manager;

use App\Entity\Articles;
use App\Entity\Order;
use App\Entity\Users;
use App\Services\StripeService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class ArticlesManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em ;

    /**
     * @var StripeService   
     */
    protected $stripeService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param StripeService $stripeService
     */
    public function __construct(EntityManagerInterface $entityManager, StripeService $stripeService)
    {
        $this->em = $entityManager;
        $this->stripeService = $stripeService;
    }

    public function intentSecret(Articles $articles)
    {
        $intent = $this->stripeService->paymentIntent($articles);

        return $intent['client_secret'] ?? null;

    }

    public function stripe(array $stripeParameter, Articles $articles){
        $ressource = null;
        $data = $this->stripeService->stripe( $stripeParameter, $articles);

        if($data)
        {
            $ressource = [
                'stripeBrand' => $data['charges']['data'][0]['payment_method_details']['card']['brand'],
                'stripeLast4' => $data['charges']['data'][0]['payment_method_details']['card']['last4'],
                'stripeId' => $data['charges']['data'][0]['id'],
                'stripeStatus' => $data['charges']['data'][0]['status'],
                'stripeToken' => $data['client_secret']
            ];
        }
        return $ressource;
    }

    public function create_subscription(array $ressource, Articles $articles, Users $users)
    {
        $order  = new Order();
        $order->getUsers($users);
        $order->getArticles($articles);
        $order->setPrice($articles->getPrix());
        $order->setReference(uniqid('', false));
        $order->setBrandStripe($ressource['stripeBrand']);
        $order->setLast4Stripe($ressource['stripeLast4']);
        $order->setIdChargeStripe($ressource['stripeId']);
        $order->setStripeToken($ressource['stripeToken']);
        $order->setStatusStripe($ressource['stripeStatus']);
        $order->setUpdatedAt(new \DateTime());
        $order->setCreatedAt(new \DateTime());
        $this->em->persist($order);
        $this->em->flush();


    }
}
