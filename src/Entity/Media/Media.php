<?php

namespace App\Entity\Media;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampableTrait;
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
	'image' => Image::class,
	'image_page' => ImagePage::class,
])]
#[Vich\Uploadable]
class Media
{
	use IdTrait;
	use TimestampableTrait;

	#[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
	protected ?string $name = null;

	#[ORM\Column(type: Types::STRING, length: 255)]
	protected ?string $url = null;

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
}
