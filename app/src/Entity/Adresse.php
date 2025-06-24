<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    private ?string $adress_number = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 5)]
    private ?string $postal = null;

    #[ORM\Column(length: 50)]
    private ?string $countries = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $adress_complement = null;

    #[ORM\ManyToOne(inversedBy: 'adresses')]
    private ?Users $users = null;

    /**
     * @var Collection<int, Commands>
     */
    #[ORM\OneToMany(targetEntity: Commands::class, mappedBy: 'adresse')]
    private Collection $commands;

    public function __construct()
    {
        $this->commands = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdressNumber(): ?string
    {
        return $this->adress_number;
    }

    public function setAdressNumber(string $adress_number): static
    {
        $this->adress_number = $adress_number;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPostal(): ?string
    {
        return $this->postal;
    }

    public function setPostal(string $postal): static
    {
        $this->postal = $postal;

        return $this;
    }

    public function getCountries(): ?string
    {
        return $this->countries;
    }

    public function setCountries(string $countries): static
    {
        $this->countries = $countries;

        return $this;
    }

    public function getAdressComplement(): ?string
    {
        return $this->adress_complement;
    }

    public function setAdressComplement(?string $adress_complement): static
    {
        $this->adress_complement = $adress_complement;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection<int, Commands>
     */
    public function getCommands(): Collection
    {
        return $this->commands;
    }

    public function addCommand(Commands $command): static
    {
        if (!$this->commands->contains($command)) {
            $this->commands->add($command);
            $command->setAdresse($this);
        }

        return $this;
    }

    public function removeCommand(Commands $command): static
    {
        if ($this->commands->removeElement($command)) {
            // set the owning side to null (unless already changed)
            if ($command->getAdresse() === $this) {
                $command->setAdresse(null);
            }
        }

        return $this;
    }
}
