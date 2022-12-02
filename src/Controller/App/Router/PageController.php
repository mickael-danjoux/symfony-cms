<?php

namespace App\Controller\App\Router;

use App\Entity\Page\Page;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends AbstractController
{
    use RouterControllerTrait;

    public function display(Request $request): Response
    {
        $page = $this->getPageOrNotFound($request);

		if ($page->getHtmlCss() && !$page->getHtmlCss() == []) {
			$css = $page->getHtmlCss()[0]['css'];
			$html = $page->getHtmlCss()[0]['html'];
		}

        return $this->render('app/router/display.html.twig', [
            'page' => $page,
			'css' => $css ?? null,
			'html' => $html ?? null
        ]);
    }
}
