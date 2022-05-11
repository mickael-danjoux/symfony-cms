<?php

namespace App\Entity\User;

use App\Repository\User\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer extends User
{
    #[ORM\Column(type: 'string', length: 50)]
	#[Assert\NotBlank(message: 'Veuillez renseigner votre prénom')]
	#[Assert\Length( max: 50, maxMessage: 'Votre prénom ne doit pas dépasser {{ limit }} caractères')]
	private string $firstName;
	

    #[ORM\Column(type: 'string', length: 50)]
	#[Assert\NotBlank(message: 'Veuillez renseigner votre nom')]
	#[Assert\Length( max: 50, maxMessage: 'Votre nom ne doit pas dépasser {{ limit }} caractères')]
	private string $lastName;
	

    #[ORM\Column(type: 'string', length: 20)]
	#[Assert\NotBlank(message: 'Veuillez renseigner votre numéro de téléphone')]
	#[Assert\Length( max: 20, maxMessage: 'Votre numéro de téléphone ne doit pas dépasser {{ limit }} caractères')]
	private string $phone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
