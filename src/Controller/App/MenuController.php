<?php

namespace App\Controller\App;

use App\Repository\Category\MenuCategoryRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends AbstractController
{
    public function __construct(private readonly MenuCategoryRepository $menuCategoryRepository, private readonly LoggerInterface $logger)
    {}

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
