<?php

namespace App\Factories\Page;

use App\Entity\Page\Page;
use App\Enum\PageTypeEnum;
use App\Repository\Page\PageRepository;
use Doctrine\ORM\EntityManagerInterface;

class PageFactory
{
    public function __construct(
        private readonly PageRepository $pageRepo,
        private readonly EntityManagerInterface $em
    )
    {
    }

    public function createForForm(): Page
    {
        $count = $this->pageRepo->findNextId();
        $page = new Page();
        $page->setTitle(Page::PAGE_DEFAULT_TITLE_PREFIX . $count);
        $page->setPath($page->getTitle());
        $page->setRouteName(Page::PAGE_ROUTE_NAME_PREFIX . $count);
        $page->generateSlug();
        $page->setPublished(false);
        $page->setController(Page::PAGE_CONTROLLER_PATH);
        $page->setType(PageTypeEnum::CUSTOM_PAGE);
        $this->em->persist($page);
        $this->em->flush();

        return $page;
    }

}
