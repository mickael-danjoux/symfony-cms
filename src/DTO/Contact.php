<?php


namespace App\DTO;


use Symfony\Component\Validator\Constraints as Assert;

class Contact
{

    #[Assert\NotBlank(message: 'Veuillez renseigner votre prénom')]
    private string $firstName;


    #[Assert\NotBlank(message: 'Veuillez renseigner votre nom')]
    private string $lastName;

    #[Assert\NotBlank(message: 'Veuillez renseigner votre téléphone')]
    private string $phone;


    #[Assert\NotBlank(message: 'Veuillez renseigner votre email')]
    #[Assert\Email(message: 'Cet email n\'est pas valide')]
    private string $email;


    #[Assert\NotBlank(message: 'Veuillez cocher la case concernant vos données')]
    private bool $agreeTerms;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez renseigner votre message")
     */
    #[Assert\NotBlank(message: 'Veuillez renseigner votre message')]
    private string $message;

    private ?string $company;

    private ?string $address;

    private ?string $zipCode;

    private ?string $city;

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

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
     * @return bool
     */
    public function isAgreeTerms(): bool
    {
        return $this->agreeTerms;
    }

    /**
     * @param bool $agreeTerms
     */
    public function setAgreeTerms(bool $agreeTerms): void
    {
        $this->agreeTerms = $agreeTerms;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = htmlspecialchars($message);
    }

    /**
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * @param string|null $company
     */
    public function setCompany(?string $company): void
    {
        $this->company = $company;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    /**
     * @param string|null $zipCode
     */
    public function setZipCode(?string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }



}
