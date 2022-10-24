<?php

namespace App\Controller\App\Legal;

use App\Controller\App\Router\RouterControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LegalController extends AbstractController
{
    use RouterControllerTrait;

    public function mentions(Request $request): Response
    {
        $page = $this->getPageOrNotFound($request);

        return $this->render('app/legal/legal.html.twig', [
            'page' => $page
        ]);
    }

}
