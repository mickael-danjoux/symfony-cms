<?php

namespace App\Controller\App\Router;

use App\Entity\Page\Page;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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


    public function __construct(EntityManagerInterface $em)
    {

    }

    protected function getPageOrNotFound(Request $request)
    {
        $page = $this->em->find(Page::class, $request->attributes->get('_pageId'));
        $preview = boolval($request->query->get('preview'));

        // Gestion de la preview et des paramètres d'activation de la page
        if (!$preview) {
            $now = new \DateTime();
            if ((!$page->isPublished()) || ($now < $page->getStartPublishingAt()) || ($page->getEndPublishingAt() != null && $now > $page->getEndPublishingAt())) {
               throw new NotFoundHttpException();
            }
        }

        return $page;
    }

}
