<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Controller\HelloController;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: Pokemon::class)]
    private Collection $Pokemons;

    #[ORM\Column]
    private ?int $generation = null;


    public function __construct()
    {
        $this->Pokemons = new ArrayCollection();
        $this->generation = 1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Pokemon>
     */
    public function getPokemons(): Collection
    {
        return $this->Pokemons;
    }

    public function addPokemon(Pokemon $pokemon): static
    {
        if (!$this->Pokemons->contains($pokemon)) {
            $this->Pokemons->add($pokemon);
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): static
    {
        $this->Pokemons->removeElement($pokemon);

        return $this;
    }

    public function getGeneration(): ?int
    {
        return $this->generation;
    }

    public function setGeneration(int $generation): static
    {
        $this->generation = $generation;

        return $this;
    }


    public function getPokemonsByGeneration($gen): Collection
    {
        return $this->Pokemons->filter(function (Pokemon $pkmn) use ($gen) {
            return $pkmn->getGeneration() === $gen;
        });
    }



    // Utilities

    public function checkIfAllWereGuessed(Collection $pokemons): bool
    {
        $count = $pokemons->count();

        if ($this->generation === 1 && $count >= 151) {
            return true;
        }

        if ($this->generation === 2 && $count >= 100) {
            return true;
        }

        if ($this->generation === 3 && $count >= 135) {
            return true;
        }

        if ($this->generation === 4 && $count >= 107) {
            return true;
        }

        if ($this->generation === 5 && $count >= 156) {
            return true;
        }

        if ($this->generation === 6 && $count >= 72) {
            return true;
        }

        if ($this->generation === 7 && $count >= 88) {
            return true;
        }

        if ($this->generation === 8 && $count >= 96) {
            return true;
        }

        if ($this->generation === 9 && $count >= 120) {
            return true;
        }

        return false;
    }

    public function getRandomPokeIdThatWasNotGuessedBefore(Collection $pokemons, ?Request $request = null): int
    {

        if ($request !== null) {
            $request->getSession()->set('badGuessStreak', 0);
        }


        $min = 1;
        $max = 1025;



        if ($this->checkIfAllWereGuessed($pokemons)) {
            return HelloController::ALL_WERE_GUESSED_CODE;
        }

        if ($this->generation === 1) {
            $min = 1;
            $max = 151;
        }
        if ($this->generation === 2) {
            $min = 152;
            $max = 251;
        }
        if ($this->generation === 3) {
            $min = 252;
            $max = 386;
        }
        if ($this->generation === 4) {
            $min = 387;
            $max = 493;
        }
        if ($this->generation === 5) {
            $min = 494;
            $max = 649;
        }
        if ($this->generation === 6) {
            $min = 650;
            $max = 721;
        }
        if ($this->generation === 7) {
            $min = 722;
            $max = 809;
        }
        if ($this->generation === 8) {
            $min = 809;
            $max = 905;
        }
        if ($this->generation === 9) {
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

    public function resolveGenerationFromId(int $id): int
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

    public function createHiddenName(string $name, int $badGuessStreak): string
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
}
