<?php

namespace App\Entity\Media;

use App\Entity\Page\Page;
use App\Repository\Media\ImagePageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name:'media_image_page')]
#[ORM\Entity(repositoryClass: ImagePageRepository::class)]
class ImagePage extends Image
{
    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Page $page = null;

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }
}
