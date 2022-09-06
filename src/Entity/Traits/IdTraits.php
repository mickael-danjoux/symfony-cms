<?php

namespace App\Entity\Traits;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait IdTraits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    protected ?int $id = null;

    /**
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

}