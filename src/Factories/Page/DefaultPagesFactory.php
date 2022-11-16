<?php

namespace App\Factories\Page;

use App\Entity\Page\Page;
use App\Entity\Seo\Seo;
use Doctrine\ORM\EntityManagerInterface;

class DefaultPagesFactory
{

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function createAll(): void
    {
        foreach (DefaultPagesArray::getList() as $page){
            $entity = new Page();
            $entity->setTitle($page['title']);
            $entity->setPath($page['path']);
            $entity->setType($page['type']);
            $entity->setController($page['controller']);
            $entity->setRouteName($page['routeName']);
            $seo = new Seo();
            $seo->setTitle($page['seo']['title']);
            $seo->setDescription($page['seo']['description']);
            $entity->setSeo($seo);
            $entity->setPublished(true);

            $this->em->persist($entity);
        }
        $this->em->flush();
    }
}
