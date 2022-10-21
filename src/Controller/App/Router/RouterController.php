<?php

namespace App\Controller\App\Router;

use App\Entity\Page\Page;
use App\Repository\Page\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RouterController extends AbstractController
{
//    #[Route('/{path}', name: 'app_router', requirements: ['path' => '^(.)*$'], priority: -1)]
//    public function router(string $path, PageRepository $pageRepository): Response
//    {
//        $page = $pageRepository->findOneByPath($path);
//        if($page instanceof Page){
//            // Besoin de la requête global à cause du Forward du router principal
//            $preview = boolval(Request::createFromGlobals()->query->get('preview'));
//
//            // Gestion de la preview et des paramètres d'activation de la page
//            if (!$preview) {
//                $now = new \DateTime();
//                if ((!$page->isPublished()) || ($now < $page->getStartPublishingAt()) || ($page->getEndPublishingAt() != null && $now > $page->getEndPublishingAt())) {
//                    return $this->redirectToRoute('app_home');
//                }
//            }
//
//            $arguments['page'] = $page;
//            return $this->forward($page->getController(), $arguments);
//        }
//        throw $this->createNotFoundException('Cette page n\'existe pas');
//    }
}
