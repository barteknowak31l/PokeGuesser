<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Pokemon;
use App\Repository\UserRepository;
use App\Controller\HelloController;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Serializer as Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;

class ApiPokemonController extends AbstractController
{


    #[Route('/api/pokemon/{generation<\d+>?1}', name: 'app_api_pokemon', methods: ['GET'])]
    public function getRandomPokemon(
        int $generation,
        PokemonRepository $pokemonRepository,
        #[CurrentUser] User $user,
        EntityManagerInterface $em,
    ): JsonResponse {


        $user->setGeneration($generation);
        $randomId = $user->getRandomPokeIdThatWasNotGuessedBefore($user->getPokemons());

        // try fetch pokemon form db
        $pokemon = $pokemonRepository->find($randomId);
        if ($pokemon instanceof Pokemon) {
            // all good we got a pkmn
        } else {
            // call poke api
            $pokemon = HelloController::callPokeApi($em, $randomId, new User());
        }

        $response =
            [
                'id' => $pokemon->getId(),
                'name' => $pokemon->getName(),
                'type1' => $pokemon->getType1(),
                'type2' => $pokemon->getType2(),
                'img' => $pokemon->getSpriteUrl(),
            ];


        return $this->json([
            'response' =>  $randomId === User::ALL_WERE_GUESSED_CODE ? null : $response,
        ]);
    }



    #[Route('/api/pokemon/add/{id<\d+>}', name: 'app_api_pokemon_add', methods: ['POST'])]
    public function addPokemon(
        int $id = null,
        PokemonRepository $pokemonRepository,
        #[CurrentUser] User $user,
        EntityManagerInterface $em,
    ): JsonResponse {


        $request_ok = false;

        // try fetch pokemon form db
        $pokemon = $pokemonRepository->find($id);
        if ($pokemon instanceof Pokemon) {


            if (!$user->getPokemons()->contains($pokemon)) {
                $user->addPokemon($pokemon);
                $em->persist($user);
                $em->flush();
                $request_ok = true;
            }
        }

        if ($request_ok) {
            $response =
                [
                    "message" => 'pokemon added'
                ];
        } else {
            $response =
                [
                    "message" => 'error'
                ];
        }


        return $this->json([
            'response' =>  $response,
        ]);
    }


    #[Route('/api/pokemon/all/{generation<\d+>}', name: 'app_api_pokemon_all', methods: ['GET'])]
    public function getAllOwnedPokemonFromGeneration(
        int $generation = null,
        #[CurrentUser] User $user,
    ): JsonResponse {


        $request_ok = false;
        if ($generation >= 1 && $generation <= 9) {
            $request_ok = true;
        }


        if ($request_ok) {

            $pokemons = $user->getPokemonsByGeneration($generation);

            // if ($pokemons->count() == 1)
            //     $pokemons = [$pokemons->first()];
            // else if ($pokemons->count() == 0)
            //     $pokemons = [];

            $pokeList = [];
            foreach ($pokemons as $pkmn) {
                array_push($pokeList, $pkmn);
            }

            $response =
                [
                    "message" => $pokeList
                ];
        } else {
            $response =
                [
                    "message" => 'error'
                ];
        }


        return $this->json([
            'response' =>  $response,
        ]);
    }
}
