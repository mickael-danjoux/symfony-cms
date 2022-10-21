<?php

namespace App\Controller\App\Router;

use App\Entity\Page\Page;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

trait RouterControllerTrait
{
    private EntityManagerInterface $em;

    /**
     * @return EntityManagerInterface
     */
    public function getEm(): EntityManagerInterface
    {
        return $this->em;
    }


    /**
     * @param EntityManagerInterface $em
     * @return void
     * @required
     */
    public function setEm(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }


    public function __construct( EntityManagerInterface $em)
    {
    }

    protected function getPage(Request $request){
       return $this->em->find(Page::class, $request->attributes->get('_pageId'));
    }

}
