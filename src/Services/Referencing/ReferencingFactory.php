<?php

namespace App\Services\Referencing;

use App\Entity\Referencing\Referencing;
use Doctrine\ORM\EntityManagerInterface;

class ReferencingFactory
{


    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function createFirstPage(): void
    {
        $referencing = new Referencing();
        $referencing->setPageName('Accueil');
        $referencing->setUrl('/');
        $referencing->setTitle('Mon site');
        $referencing->setDescription('Lorem ipsum');
        $this->em->persist($referencing);
        $this->em->flush();
    }

}