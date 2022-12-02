<?php

namespace App\Entity\Media;

use App\Repository\Media\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ORM\Table(name:'media_image')]
#[Vich\Uploadable]
class Image extends Media
{
	#[Vich\UploadableField(mapping: 'images', fileNameProperty: 'url')]
	#[Assert\Image(
		maxSize: "4M",
		mimeTypes: [
			"image/jpeg",
			"image/jpg",
			"image/JPG",
			"image/png",
		],
		mimeTypesMessage: "Le format de la photo n'est pas valide"
	)]
	protected ?SymfonyFile $file = null;

	/**
	 * @return SymfonyFile|null
	 */
	public function getFile(): ?SymfonyFile
	{
		return $this->file;
	}

	/**
	 * @param SymfonyFile|null $file
	 * @return Image
	 */
	public function setFile(?SymfonyFile $file): self
	{
		$this->file = $file;

		if (null !== $file) {
			// It is required that at least one field changes if you are using doctrine
			// otherwise the event listeners won't be called and the file is lost
			$this->updatedAt = new \DateTime();
		}

		return $this;
	}
}
