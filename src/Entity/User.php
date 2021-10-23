<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    protected string $email;

    /**
     * @ORM\Column(type="json")
     */
    protected array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    protected string $password;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    protected string $displayName;

    /**
     * @ORM\Column(type="boolean", options={"default" = 0})
     */
    protected bool $isBanned = false;

    /**
     * @ORM\Column(type="boolean", options={"default" = 0})
     */
    protected bool $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $token;

    /**
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected \DateTime $registeredAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected \DateTime $lastLoggedInAt;




    public function getId(): ?int
    {
        return $this->id;
    }

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
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
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
     */
    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
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
     */
    public function setIsBanned(bool $isBanned): void
    {
        $this->isBanned = $isBanned;
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
     */
    public function setIsVerified(bool $isVerified): void
    {
        $this->isVerified = $isVerified;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
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
