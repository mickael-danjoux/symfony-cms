<?php

namespace App\Controller\App;

use App\Controller\App\Router\RouterControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    use RouterControllerTrait;
    public function index(Request $request): Response
    {
        return $this->render('app/home/home.html.twig', [
            'page' => $this->getPageOrNotFound($request)
        ]);
    }
}
