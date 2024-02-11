<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $user1 = new User();
        $user1->setEmail('robb@stark.com');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, '123456'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('bran@stark.com');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, '123456'));
        $manager->persist($user2);

        $user3 = new User();
        $user3->setEmail('arya@stark.com');
        $user3->setPassword($this->passwordHasher->hashPassword($user3, '123456'));
        $manager->persist($user3);


        $manager->flush();
    }
}
