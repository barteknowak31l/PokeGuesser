<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApiController extends AbstractController
{

    private const REPSONSE_REGISTER_OK = 'User successfully registered';
    private const REPSONSE_REGISTER_BAD = 'Registration failure';


    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(#[CurrentUser] $user = null): Response
    {

        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        #
        # CREATE AN API TOKEN
        #

        return $this->json([
            'id' => $user->getId()
        ]);
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        $data = json_decode($request->getContent(), true);
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->submit($data);



        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->json([
                'message' => ApiController::REPSONSE_REGISTER_OK
            ]);
        }



        return $this->json([
            'message' => ApiController::REPSONSE_REGISTER_BAD
        ]);
    }
}
