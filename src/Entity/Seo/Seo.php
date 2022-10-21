<?php

namespace App\Entity\Seo;

use App\Entity\Traits\IdTrait;
use App\Repository\Seo\SeoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: SeoRepository::class)]
class Seo
{
    use IdTrait;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    #[Length(max: 255, maxMessage: 'Le titre ne doit pas dépasser {{ limit }} caractères.')]
    private ?string $title = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    #[Length(max: 255, maxMessage: 'La description ne doit pas dépasser {{ limit }} caractères.')]
    private ?string $description = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
