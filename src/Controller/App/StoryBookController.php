<?php

namespace App\Controller\App;

use App\Controller\App\Router\AbstractRouterController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StoryBookController extends AbstractRouterController
{

    public function index(Request $request): Response
    {
        $buttonsClasses = [
            'primary' => [
                'name' => 'Primary',
                'class' => 'btn btn-primary'
            ],
            'secondary' => [
                'name' => 'Secondary',
                'class' => 'btn btn-secondary'
            ],
            'succcess' => [
                'name' => 'Success',
                'class' => 'btn btn-success'
            ],
            'danger' => [
                'name' => 'Danger',
                'class' => 'btn btn-danger'
            ],
            'info' => [
                'name' => 'Info',
                'class' => 'btn btn-info'
            ],
            'add' => [
                'name' => 'Add (primary)',
                'class' => 'btn btn-primary btn-add'
            ],
            'previous' => [
                'name' => 'Previous (primary)',
                'class' => 'btn btn-primary btn-previous'
            ],
            'next' => [
                'name' => 'Next (primary)',
                'class' => 'btn btn-primary btn-next'
            ],
        ];

        $fontsClasses = [
            'h1' => 'Titre h1',
            'h2' => 'Titre h2',
            'h3' => 'Titre h3',
            'h4' => 'Titre h4',
            'h5' => 'Titre h5',
            'h6' => 'Titre h6',
            'p' => 'Exemple de paragraphe.',
            'span' => 'Span'
        ];


        return $this->render('app/story_book/index.html.twig', [
            'page' => $this->getPageOrNotFound($request),
            'buttonsClasses' => $buttonsClasses,
            'fontsClasses' => $fontsClasses
        ]);
    }
}
