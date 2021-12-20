<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
// extends Fixture
class UsersFixtures 
{

    public function load(ObjectManager $manager){

        for($i = 0 ; $i < 10 ; $i++){
            $user = (new Users())
            ->setEmail("user$i@domain.fr")
            ->setPassword("0000");
            $manager->persist($user);
        }
        $manager->flush();

    }
}