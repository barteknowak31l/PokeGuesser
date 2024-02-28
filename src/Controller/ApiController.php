<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApiController extends AbstractController
{
    #[Route('/api/register', name: 'app_api_register', methods: ['POST'])]
    public function apiRegister(
        Request $request,
        UserRepository $users,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $userPasswordHasher,
        LoggerInterface $logger
    ): Response {

        $requestOk = false;
        $userData = json_decode($request->getContent(), true);


        if (isset($userData['username']) && isset($userData['password'])) {
            $requestOk = true;
        }


        if ($requestOk) {
            $email = $userData['username'];
            $password = $userData['password'];


            $logger->log(3, $email);

            $checkUser = $users->getUserByEmail($email);

            $logger->log(3, count($checkUser));


            if (count($checkUser) > 0) {
                // user already exists
                return $this->json(
                    [
                        'message' => 'error, user exists'
                    ],
                    400
                );
            }


            // create new user
            $user = new User();
            $user->setEmail($email);

            // hash password:
            $hashedPassword = $userPasswordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
            $em->persist($user);
            $em->flush();

            return $this->json(
                [
                    'message' => 'ok, user created'
                ],
                200
            );
        }


        return $this->json(
            [
                'message' => 'error, missing credentials'
            ],
            400
        );
    }
}
