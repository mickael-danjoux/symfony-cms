<?php


namespace App\Classes;


use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserDTO
 * @package App\Classes
 */
class UserDTO
{
    #[Assert\NotBlank(message: 'Veuillez renseigner votre email')]
    #[Assert\Email(message: 'Cet email n\'est pas valide')]
    private string $email;


    #[Assert\NotBlank(message: 'Veuillez renseigner le mot de passe')]
    #[Assert\Length( min: 7, minMessage: 'Ce mot de passe est trop court')]
    private string $password;


    #[Assert\NotBlank(message: 'Veuillez repéter le mot de passe')]
    #[Assert\EqualTo( value: 'password' ,message: 'Veuillez repéter le mot de passe')]
    private string $repeatedPassword;


    #[Assert\NotBlank(message: 'Veuillez renseigner le nom afficher')]
    private string $displayName;


    #[Assert\NotBlank(message: 'Veuillez renseigner si le compte est validé')]
    private bool $isVerified = false;

    private array $roles = [];

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getRepeatedPassword(): string
    {
        return $this->repeatedPassword;
    }

    /**
     * @param string $repeatedPassword
     */
    public function setRepeatedPassword(string $repeatedPassword): void
    {
        $this->repeatedPassword = $repeatedPassword;
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
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array|string $roles
     */
    public function setRoles(mixed $roles): void
    {
        if (is_string($roles)) {
            $this->roles[] = $roles;
        } else {
            $this->roles = $roles;
        }
    }

    public function hydrate(UserPasswordHasherInterface $hasher)
    {
        try {
            $user = new User();

            $this->setPassword(
                $hasher->hashPassword(
                    $user,
                    $this->password
                )
            );

            $user->setEmail($this->email)
                ->setPassword($this->password)
                ->setDisplayName($this->displayName)
                ->setIsVerified($this->isVerified())
                ->setRoles($this->roles);
            return $user;
        } catch (\Exception $e) {
            throw $e;
        }

    }


}
