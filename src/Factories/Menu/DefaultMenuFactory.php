<?php

namespace App\Factories\Menu;

use App\Entity\Category\Category;
use App\Entity\Category\MenuCategory;
use App\Enum\MenuCategoryTypeEnum;
use App\Repository\Category\MenuCategoryRepository;
use App\Repository\Page\PageRepository;
use Doctrine\ORM\EntityManagerInterface;

class DefaultMenuFactory
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MenuCategoryRepository $repo,
        private readonly PageRepository$pageRepo,
    )
    {
    }

    public function createAll(): void
    {
        $root = $this->repo->findOneBySlug(Category::MENU_SLUG);

        foreach (DefaultMenuArray::getList() as $item){
            $entity = new MenuCategory();
            $entity->setTitle($item['title']);
            $entity->setsort($item['sort']);
            $entity->setType($item['type']);
            $entity->setParent($root);
            if($item['type'] === MenuCategoryTypeEnum::INTERNAL_LINK){
                $entity->setPage($this->pageRepo->findOneByPath($item['pagePath']));
            }
            if(isset($item['itemId'])){
                $entity->setItemClasses($item['itemId']);
            }
            if(isset($item['itemClasses'])){
                $entity->setItemClasses($item['itemClasses']);
            }
            $this->em->persist($entity);

        }
        $this->em->flush();

    }

}
