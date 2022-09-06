<?php

namespace App\Entity\User;

use App\Entity\Traits\IdTrait;
use App\Repository\User\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Cet email existe déjà')]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "discr", type: Types::STRING)]
#[ORM\DiscriminatorMap(["user" => User::class, "customer" => Customer::class])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    use IdTrait;

    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner votre email')]
    #[Assert\Email(message: 'Cet email n\'est pas valide')]
    protected string|null $email = null;


    #[ORM\Column(type: Types::JSON)]
    protected array $roles = [];


    #[ORM\Column(type: Types::STRING)]
    protected string|null $password = null;


    #[ORM\Column(type: Types::STRING)]
    protected string $displayName;


    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0] )]
    protected bool $isBanned = false;


    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0] )]
    protected bool $isVerified = false;


    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    protected string|null $token;
	
	
	#[ORM\Column(type: Types::DATETIME_MUTABLE, length: 255, nullable: true)]
	protected \DateTime|null $tokenDisabledAt;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE )]
    protected \DateTime $registeredAt;
	
    #[ORM\Column(type: Types::DATETIME_MUTABLE , nullable: true)]
    protected \DateTime $lastLoggedInAt;


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     * @return User
     */
    public function setDisplayName(string $displayName): User
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->isBanned;
    }

    /**
     * @param bool $isBanned
     * @return User
     */
    public function setIsBanned(bool $isBanned): User
    {
        $this->isBanned = $isBanned;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    /**
     * @param bool $isVerified
     * @return User
     */
    public function setIsVerified(bool $isVerified): User
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string|null
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return User
     */
    public function setToken(string|null $token): User
    {
        $this->token = $token;

        return $this;
    }
	
	/**
	 * @return \DateTime|null
	 */
	public function getTokenDisabledAt(): ?\DateTime
	{
		return $this->tokenDisabledAt;
	}
	
	/**
	 * @param \DateTime|null $tokenDisabledAt
	 */
	public function setTokenDisabledAt(?\DateTime $tokenDisabledAt): void
	{
		$this->tokenDisabledAt = $tokenDisabledAt;
	}

    /**
     * @return \DateTime
     */
    public function getRegisteredAt(): \DateTime
    {
        return $this->registeredAt;
    }


    /**
     * @return \DateTime
     */
    public function getLastLoggedInAt(): \DateTime
    {
        return $this->lastLoggedInAt;
    }

    /**
     * @param \DateTime $lastLoggedInAt
     */
    public function setLastLoggedInAt(\DateTime $lastLoggedInAt): void
    {
        $this->lastLoggedInAt = $lastLoggedInAt;
    }


}
