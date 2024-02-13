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
    private const SHOW_AFTER_X_BAD_ANSWER = 1;
    private const ALL_WERE_GUESSED_CODE = -2;


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


        // get bad attempt (number of concurrent bad guesses) counter
        $badGuessStreak = $request->getSession()->get('badGuessStreak', 0);



        // if user got there any other way than a redirect
        // we must clear bad guess streak
        if ($request->isMethod('GET') && !$request->headers->has('referer')) {
            $badGuessStreak = 0;
            $this->clearBadStreak($request);
        }

        // check if this pokemon is already in our database 
        $pokemon = $pokemons->find($randomId);
        if ($pokemon instanceof Pokemon) {

            // we got a pokemon!
            // just send it to the form

        } else {
            // unfortunately, we need to call poke api
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


            // resolve generation:
            $pkmnGeneration = $this->resolveGenerationFromId($id);

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
        }

        // create a pokemon form

        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        $userGeneration = $user->getGeneration();
        $userPokemons = $user->getPokemonsByGeneration($userGeneration);
        if ($this->checkIfAllWereGuessed($userPokemons, $userGeneration)) {
            return $this->redirectToRoute('app_all_were_guessed', ['generation' => $userGeneration]);
        }


        if ($form->isSubmitted() && $form->isValid()) {



            // check if answer is correct
            $answerValue = strtolower($_POST['pokemonType']['answer']);
            $otherPokemon = $form->getData();

            // correct answer
            if ($answerValue === $otherPokemon->getName()) {
                $this->addFlash('success', "Correct Answer!");

                // add pokemon to this user
                $user->addPokemon($otherPokemon);
                $em->persist($user);
                $em->flush();
                $randomId = $this->getRandomId($userPokemons, $userGeneration, $request);
                if ($randomId === HelloController::ALL_WERE_GUESSED_CODE) {
                    return $this->redirectToRoute('app_all_were_guessed', ['generation' => $userGeneration]);
                }
                return $this->redirectToRoute('app_hello', ['randomId' => $randomId,  'generation' => $user->getGeneration()]);
            } else {
                // wrong answer
                $this->addFlash('failure', "Wrong Answer!");
                $randomId = $otherPokemon->getId();
                $request->getSession()->set('badGuessStreak', $badGuessStreak + 1);

                return $this->redirectToRoute('app_hello', ['randomId' => $randomId,  'generation' => $user->getGeneration()]);
            }
        }


        // prepare hidden name for hint
        $hiddenName = $this->prepareHiddenName($pokemon->getName(), $badGuessStreak);

        return $this->render('hello/index.html.twig', [
            'pokemon' => $pokemon,
            'form' => $form->createView(),
            'hiddenName' => $hiddenName,
        ]);
    }


    private function clearBadStreak(Request $request): void
    {
        $request->getSession()->set('badGuessStreak', 0);
    }

    private function getRandomId(Collection $pokemons, int $generation, Request $request): int
    {

        $this->clearBadStreak($request);

        $min = 1;
        $max = 1025;



        if ($this->checkIfAllWereGuessed($pokemons, $generation)) {
            return HelloController::ALL_WERE_GUESSED_CODE;
        }

        if ($generation === 1) {
            $min = 1;
            $max = 151;
        }
        if ($generation === 2) {
            $min = 152;
            $max = 251;
        }
        if ($generation === 3) {
            $min = 252;
            $max = 386;
        }
        if ($generation === 4) {
            $min = 387;
            $max = 493;
        }
        if ($generation === 5) {
            $min = 494;
            $max = 649;
        }
        if ($generation === 6) {
            $min = 650;
            $max = 721;
        }
        if ($generation === 7) {
            $min = 722;
            $max = 809;
        }
        if ($generation === 8) {
            $min = 809;
            $max = 905;
        }
        if ($generation === 9) {
            $min = 906;
            $max = 1025;
        }

        $repeat = true;
        while ($repeat) {
            $randomId = random_int($min, $max);
            $repeat = false;
            foreach ($pokemons as $pkmn) {
                if ($pkmn->getId() === $randomId) {
                    $repeat = true;
                    break;
                }
            }
        }

        return $randomId;
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

        $randomId = $this->getRandomId($pokemons, $generation, $request);
        if ($randomId === HelloController::ALL_WERE_GUESSED_CODE) {
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


    private function checkIfIdWasGuessed(Collection $pokemons, int $id): bool
    {
        foreach ($pokemons as $pkmn) {
            if ($pkmn->getId() === $id) {
                return true;
            }
        }
        return false;
    }

    private function prepareHiddenName(string $name, int $badGuessStreak): string
    {
        $length = strlen($name);
        $showLetters = (int) ($badGuessStreak / HelloController::SHOW_AFTER_X_BAD_ANSWER);
        if ($showLetters >= $length)
            return $name;
        else {
            $resultName = '';
            foreach (str_split($name) as $i => $char) {
                if ($i >= $showLetters)
                    $resultName .= '_';
                else
                    $resultName .= $char;
            }
            return $resultName;
        }
    }

    private function resolveGenerationFromId(int $id): int
    {
        if ($id < 152) {
            return 1;
        } else if ($id < 252) {
            return 2;
        } else if ($id < 387) {
            return 3;
        } else if ($id < 494) {
            return 4;
        } else if ($id < 650) {
            return 5;
        } else if ($id < 722) {
            return 6;
        } else if ($id < 810) {
            return 7;
        } else if ($id < 906) {
            return 8;
        } else {
            return 9;
        }
    }


    private function checkIfAllWereGuessed(Collection $pokemons, int $generation): bool
    {
        $count = $pokemons->count();

        if ($generation === 1 && $count >= 151) {
            return true;
        }

        if ($generation === 2 && $count >= 100) {
            return true;
        }

        if ($generation === 3 && $count >= 135) {
            return true;
        }

        if ($generation === 4 && $count >= 107) {
            return true;
        }

        if ($generation === 5 && $count >= 156) {
            return true;
        }

        if ($generation === 6 && $count >= 72) {
            return true;
        }

        if ($generation === 7 && $count >= 88) {
            return true;
        }

        if ($generation === 8 && $count >= 96) {
            return true;
        }

        if ($generation === 9 && $count >= 120) {
            return true;
        }

        return false;
    }
}
