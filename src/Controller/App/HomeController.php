<?php

namespace App\Controller\App;

use App\Controller\App\Router\AbstractRouterController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractRouterController
{
    public function index(Request $request): Response
    {
        return $this->render('app/home/home.html.twig', [
            'page' => $this->getPageOrNotFound($request)
        ]);
    }
}
