<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use GuzzleHttp\Client;
use App\Entity\Pokemon;
use App\Form\PokemonType;
use App\Entity\Generation;
use App\Form\GenerationType;
use App\Repository\PokemonRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController
{

    // how many bad guesses until next letter is uncovered?
    public const SHOW_AFTER_X_BAD_ANSWER = 1;


    #[Route('/hello/{generation?1}/{randomId?1}', name: 'app_hello')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(
        EntityManagerInterface $em,
        PokemonRepository $pokemons,
        Request $request,
        int $randomId,
        int $generation
    ): Response {


        /**
         * @var User $user
         */
        $user = $this->getUser();


        // save user generation
        if ($user->getGeneration() !== $generation) {
            $user->setGeneration($generation);
            $em->persist($user);
            $em->flush();
        }


        // get bad attempt counter (number of consecutive bad guesses)
        $badGuessStreak = $request->getSession()->get('badGuessStreak', 0);


        // if user got there by any other way than a redirect
        // we must clear bad guess streak
        if ($request->isMethod('GET') && !$request->headers->has('referer')) {
            $badGuessStreak = 0;
            $request->getSession()->set('badGuessStreak', 0);
        }

        // check if this pokemon is already in our database 
        $pokemon = $pokemons->find($randomId);
        if ($pokemon instanceof Pokemon) {

            // we got a pokemon!
            // just send it to the form

        } else {

            // unfortunately, we need to call poke api
            $pokemon = $this->callPokeApi($em, $randomId, $user);
        }

        // create and handle a pokemon form
        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        // get useful data required to further processing 
        $userGeneration = $user->getGeneration();
        $userPokemons = $user->getPokemonsByGeneration($userGeneration);

        // when all pokemons from a given gen were guessed, show a special message
        if ($user->checkIfAllWereGuessed($userPokemons)) {
            return $this->redirectToRoute('app_all_were_guessed', ['generation' => $userGeneration]);
        }


        if ($form->isSubmitted() && $form->isValid()) {
            // check if answer is correct

            // value from _POST table of field 'answer' from a from ('answer' is not mapped to Pokemon class)
            $answerValue = strtolower($_POST['pokemonType']['answer']);
            $otherPokemonId = $form->getData()->getId('id');
            $otherPokemon = $pokemons->find($otherPokemonId);

            // correct answer
            if ($answerValue === $otherPokemon->getName()) {

                $this->addFlash('success', "Correct Answer!");

                // add pokemon to this user
                $user->addPokemon($otherPokemon);
                $em->persist($user);
                $em->flush();

                $request->getSession()->set('badGuessStreak', 0);
                $randomId = $user->getRandomPokeIdThatWasNotGuessedBefore($userPokemons, $request);
                if ($randomId === User::ALL_WERE_GUESSED_CODE) {
                    return $this->redirectToRoute('app_all_were_guessed', ['generation' => $userGeneration]);
                }

                return $this->redirectToRoute('app_hello', ['randomId' => $randomId,  'generation' => $userGeneration]);
            } else {

                // wrong answer
                $this->addFlash('failure', "Wrong Answer!");
                $randomId = $otherPokemon->getId();
                $request->getSession()->set('badGuessStreak', $badGuessStreak + 1);

                return $this->redirectToRoute('app_hello', ['randomId' => $randomId,  'generation' => $userGeneration]);
            }
        }


        // prepare hidden name for hint
        $hiddenName = $user->createHiddenName($pokemon->getName(), $badGuessStreak);

        return $this->render('hello/index.html.twig', [
            'pokemon' => $pokemon,
            'form' => $form->createView(),
            'hiddenName' => $hiddenName,
        ]);
    }


    #[Route('/afterLogin/{generation?1}', name: 'app_after_login')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function afterLogin(EntityManagerInterface $em, Request $request, int $generation): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $pokemons = $user->getPokemonsByGeneration($generation);
        $user->setGeneration($generation);
        $em->persist($user);
        $em->flush();

        $randomId = $user->getRandomPokeIdThatWasNotGuessedBefore($pokemons, $request);
        if ($randomId === User::ALL_WERE_GUESSED_CODE) {
            return $this->redirectToRoute('app_all_were_guessed', ['generation' => $generation]);
        }

        return $this->redirectToRoute('app_hello', [
            'randomId' => $randomId,
            'generation' => $user->getGeneration()
        ]);
    }


    #[Route('/allWereGuessed/{generation?1}}', name: 'app_all_were_guessed')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function allWereGuessed(EntityManagerInterface $em, int $generation): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        return $this->render('hello/all_were_guessed.html.twig', [
            'generation' => $user->getGeneration()
        ]);
    }


    public static function callPokeApi(EntityManagerInterface $em, int $randomId, User $user): Pokemon
    {
        $apiUrl = 'https://pokeapi.co/api/v2/pokemon/' . $randomId;
        $client = new Client();
        $response = $client->get($apiUrl);

        $apiData = $response->getBody()->getContents();

        $data = json_decode($apiData, true);

        // we need id, name, types, and sprite url
        $id = null;
        $name = null;
        $type1 = null;
        $type2 = null;
        $spriteUrl = null;


        if (isset($data['id'])) {
            $id = $data['id'];
        }

        if (isset($data['name'])) {
            $name = $data['name'];
        }

        if (isset($data['types'][0]['type']['name'])) {
            $type1 = $data['types'][0]['type']['name'];
        }
        if (isset($data['types'][1]['type']['name'])) {
            $type2 = $data['types'][1]['type']['name'];
        }
        if (isset($data['sprites']['front_default'])) {
            $spriteUrl = $data['sprites']['front_default'];
        }


        // resolve pokemon generation
        $pkmnGeneration = $user->resolveGenerationFromId($id);

        // we can create a new Pokemon instance now
        $pokemon = new Pokemon();
        $pokemon->setId($id);
        $pokemon->setName($name);
        $pokemon->setType1($type1);
        $pokemon->setType2($type2);
        $pokemon->setGeneration($pkmnGeneration);
        $pokemon->setSpriteUrl($spriteUrl);

        // and serialize it
        $em->persist($pokemon);
        $em->flush();


        return $pokemon;
    }
}
