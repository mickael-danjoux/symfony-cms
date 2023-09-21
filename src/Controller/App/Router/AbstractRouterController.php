<?php

namespace App\Controller\App\Router;

use App\Entity\Page\Page;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Service\Attribute\Required;

Abstract class AbstractRouterController extends AbstractController
{

    protected EntityManagerInterface $em;
    protected LoggerInterface $logger;


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

    #[Required]
    public function setEntityManager(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    #[Required]
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }


}