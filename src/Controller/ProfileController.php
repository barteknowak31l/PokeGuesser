<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PokemonRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    #[Route('/profile/{generation?-1}', name: 'app_profile')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(
        UserRepository $users,
        EntityManagerInterface $em,
        int $generation
    ): Response {

        /**
         * @var User $user
         */
        $user = $this->getUser();
        if ($generation === -1) {
            $generation = $user->getGeneration();
        }

        // if generation has changed, also change it on user and save to db
        if ($generation !== $user->getGeneration()) {
            $user->setGeneration($generation);
            $em->persist($user);
            $em->flush();
        }

        $pokemonList = $users->getUserWithPokemonsByGeneration($user->getId(), $generation);

        if (!empty($pokemonList)) {
            $pokemonList = $pokemonList[0]->getPokemons();
        }
        return $this->render('profile/index.html.twig', [
            'pokemonList' => $pokemonList,
        ]);
    }
}
