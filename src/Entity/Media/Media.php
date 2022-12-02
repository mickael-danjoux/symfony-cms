<?php

namespace App\Entity\Media;

use App\Repository\Media\MediaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[InheritanceType("JOINED")]
#[DiscriminatorColumn(name:'discr', type:'string')]
#[DiscriminatorMap([
	'media' => Media::class,
	'image' => Image::class
])]
#[Vich\Uploadable]
class Media
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: Types::INTEGER)]
	protected ?int $id = null;

	#[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
	protected ?string $name = null;

	#[ORM\Column(type: Types::STRING, length: 255)]
	protected ?string $url = null;

	#[ORM\Column(type: Types::DATETIME_MUTABLE)]
	protected ?\DateTime $updatedAt;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(?string $name): self
	{
		$this->name = $name;

		return $this;
	}

	public function getUrl(): ?string
	{
		return $this->url;
	}

	public function setUrl(?string $url): self
	{
		$this->url = $url;

		return $this;
	}

	public function getUpdatedAt(): ?\DateTime
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt(?\DateTime $updatedAt): self
	{
		$this->updatedAt = $updatedAt;

		return $this;
	}
}
