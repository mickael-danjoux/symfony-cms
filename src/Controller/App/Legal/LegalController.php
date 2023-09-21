<?php

namespace App\Controller\App\Legal;

use App\Controller\App\Router\AbstractRouterController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LegalController extends AbstractRouterController
{
    public function mentions(Request $request): Response
    {
        $page = $this->getPageOrNotFound($request);

        return $this->render('app/legal/legal.html.twig', [
            'page' => $page
        ]);
    }

}
