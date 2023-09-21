<?php

namespace App\Controller\App\Render;

use App\Controller\App\Router\AbstractRouterController;
use App\Repository\Category\MenuCategoryRepository;
use Symfony\Component\HttpFoundation\Response;

class MenuRenderController extends AbstractRouterController
{
    public function __construct(private readonly MenuCategoryRepository $menuCategoryRepository)
    {
    }

    public function show(): Response
    {
        $menu = [];

        try {
            $menu = $this->menuCategoryRepository->getMenu();
        } catch (\Exception $e) {
            $this->logger->error('ERREUR Menu : ' . $e->getMessage(), ['Erreur' => $e]);
        }

        return $this->render('app/partials/_menu.html.twig', [
            'menu' => $menu,
        ]);
    }
}
