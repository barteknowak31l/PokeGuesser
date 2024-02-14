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

    private const RESPONSE_ALL_WERE_GUESSED = 'Congratulations! All pokemons from this generation were guessed!';
    private const RESPONSE_OK = 'Ok';
    private const RESPONSE_CORRECT_ANSWER = 'Correct Answer!';
    private const RESPONSE_WRONG_ANSWER = 'Wrong Answer!';
    private const RESPONSE_WRONG_ID = 'Wrong id!';
    private const RESPONSE_MISSING_DATA = 'Missing data!';
    private const RESPONSE_SOMETHING_WENT_WRONG = 'Opps, something went wrong on the server :/ please contact our support';




    #[Route('/api/pokemon/{generation<d+>?1}', name: 'app_api_pokemon', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function getRandomPokemon(
        #[CurrentUser] User $user,
        int $generation,
        PokemonRepository $pokemonRepository,
        EntityManagerInterface $em
    ): JsonResponse {

        $pokemons = $user->getPokemonsByGeneration($generation);
        $randomId = $user->getRandomPokeIdThatWasNotGuessedBefore($pokemons);

        $message = $randomId === User::ALL_WERE_GUESSED_CODE ? ApiPokemonController::RESPONSE_ALL_WERE_GUESSED : ApiPokemonController::RESPONSE_OK;

        // we also need types, img source and encoded name

        if ($randomId !== User::ALL_WERE_GUESSED_CODE) {


            // try fetch pokemon form db
            $pokemon = $pokemonRepository->find($randomId);
            if ($pokemon instanceof Pokemon) {
                // all good we got a pkmn
            } else {
                // call poke api
                $pokemon = HelloController::callPokeApi($em, $randomId, $user);
            }

            $response =
                [
                    'id' => $pokemon->getId(),
                    'type1' => $pokemon->getType1(),
                    'type2' => $pokemon->getType2(),
                    'img' => $pokemon->getSpriteUrl(),
                ];

            // set last asked id on the user
            $user->setLastAskedId($randomId);
            $em->persist($user);
            $em->flush();
        }


        return $this->json([
            'message' => $message,
            'response' =>  $randomId === User::ALL_WERE_GUESSED_CODE ? null : $response,
        ]);
    }


    #[Route('/api/pokemon/answer', name: 'app_api_pokemon_answer', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function checkAnswer(
        #[CurrentUser] User $user,
        PokemonRepository $pokemonRepository,
        EntityManagerInterface $em,
        Request $request
    ): JsonResponse {


        if ($request->getPayload()->has('id')) {
            $id = $request->getPayload()->get('id');
        } else {
            return $this->json([
                'message' => ApiPokemonController::RESPONSE_MISSING_DATA,
            ]);
        }


        if ($request->getPayload()->has('answer')) {
            $answerName = strtolower($request->getPayload()->get('answer'));
        } else {
            return $this->json([
                'message' => ApiPokemonController::RESPONSE_MISSING_DATA,
            ]);
        }


        // check if last asked id is the id user provided answer for
        if ($id != $user->getLastAskedId() || $id == User::LAST_ASKED_DEFAULT_VALUE) {
            return $this->json([
                'message' => ApiPokemonController::RESPONSE_WRONG_ID,
            ]);
        }

        // check if answer is correct
        $pokemon = $pokemonRepository->find($id);

        // if (null === $pokemon) {
        //     return $this->json([
        //         'message' => ApiPokemonController::RESPONSE_WRONG_ID,
        //     ]);
        // }

        if ($pokemon->getName() == $answerName) {
            // correct
            $user->addPokemon($pokemon);
            $user->setLastAskedId(User::LAST_ASKED_DEFAULT_VALUE);
            $em->persist($user);
            $em->flush();

            return $this->json([
                'message' => ApiPokemonController::RESPONSE_CORRECT_ANSWER
            ]);
        } else {

            return $this->json([
                'message' => ApiPokemonController::RESPONSE_WRONG_ANSWER
            ]);
        }


        return $this->json([
            'message' => ApiPokemonController::RESPONSE_SOMETHING_WENT_WRONG
        ]);
    }


    // TODO: 
    //      endpoint for all pokemons
    //      endpoint for pokemons by generation

    #[Route('/api/pokemon/all/{generation}', name: "app_api_pokemon_all", methods: ['GET'])]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public function getAllUserPokemons(#[CurrentUser] User $user, int $generation = null): JsonResponse
    {

        if (null !== $generation) {
            return $this->json(
                [
                    'message' => 'ok',
                    'list' => $user->getPokemonsByGeneration($generation)
                ]
            );
        }

        return $this->json(
            [
                'message' => 'ok',
                'list' => $user->getPokemons()
            ]
        );
    }


    // then...  FRONTEND BABY
}
