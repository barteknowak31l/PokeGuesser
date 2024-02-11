<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use GuzzleHttp\Client;
use App\Entity\Pokemon;
use App\Form\PokemonType;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController
{

    private $SHOW_AFTER_X_BAD_ANSWER = 2;

    #[Route('/hello/{randomId?1}', name: 'app_hello')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(
        EntityManagerInterface $em,
        PokemonRepository $pokemons,
        Request $request,
        int $randomId
    ): Response {


        /**
         * @var User $user
         */
        $user = $this->getUser();

        // get bad attempt (number of concurrent bad guesses) counter
        $badGuessStreak = $request->getSession()->get('badGuessStreak', 0);

        // //fetch last id if not guessed already
        // $lastId = $request->query->get('randomId');
        // if (null !== $lastId) {
        //     $randomId = $lastId;
        // }


        // if user got there any other way than a redirect
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
            $generation = $this->resolveGenerationFromId($id);

            // we can create a new Pokemon instance now
            $pokemon = new Pokemon();
            $pokemon->setId($id);
            $pokemon->setName($name);
            $pokemon->setType1($type1);
            $pokemon->setType2($type2);
            $pokemon->setGeneration($generation);
            $pokemon->setSpriteUrl($spriteUrl);

            // and serialize it
            $em->persist($pokemon);
            $em->flush();
        }

        // create a pokemon form

        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // check if answer is correct


            $answerValue = $_POST['pokemonType']['answer'];
            $otherPokemon = $form->getData();

            // correct answer
            if ($answerValue === $otherPokemon->getName()) {
                $this->addFlash('success', "Correct Answer!");

                // add pokemon to this user
                $user->addPokemon($otherPokemon);
                $em->persist($user);
                $em->flush();
                $randomId = $this->getRandomId($user->getPokemons());
                $request->getSession()->set('badGuessStreak', 0);
                return $this->redirectToRoute('app_hello', ['randomId' => $randomId]);
            } else {
                // wrong answer
                $this->addFlash('failure', "Wrong Answer!");
                $randomId = $otherPokemon->getId();
                $request->getSession()->set('badGuessStreak', $badGuessStreak + 1);

                return $this->redirectToRoute('app_hello', ['randomId' => $randomId]);
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


    private function getRandomId(Collection $pokemons): int
    {
        $repeat = true;
        while ($repeat) {
            $randomId = random_int(1, 1025);
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

    #[Route('/afterLogin', name: 'app_after_login')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function afterLogin(): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $pokemons = $user->getPokemons();
        $randomId = $this->getRandomId($pokemons);

        return $this->redirectToRoute('app_hello', ['randomId' => $randomId]);
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
        $showLetters = (int) ($badGuessStreak / $this->SHOW_AFTER_X_BAD_ANSWER);
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
}
