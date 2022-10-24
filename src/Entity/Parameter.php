<?php

namespace App\Entity;

use App\Repository\ParameterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Sherlockode\ConfigurationBundle\Model\Parameter as BaseParameter;


#[ORM\Entity(repositoryClass: ParameterRepository::class)]
class Parameter extends BaseParameter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    protected $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    protected $path;

    #[ORM\Column(type: Types::STRING, length: 255)]
    protected $value;
}
