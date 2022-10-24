<?php

namespace App\Factories\Page;

use App\Enum\PageTypeEnum;

class DefaultPagesArray
{
    static function getList(): array
    {
        return [
            [
                'title' => 'Page d’accueil',
                'path' => '/',
                'type' => PageTypeEnum::INTERNAL_PAGE,
                'controller' => 'App\Controller\App\HomeController::index',
                'routeName' => 'app_home',
                'seo' => [
                    'title' => 'Page d’accueil',
                    'description' => ''
                ]
            ],
            [
                'title' => 'Contact',
                'path' => 'contact',
                'type' => PageTypeEnum::INTERNAL_PAGE,
                'controller' => 'App\Controller\App\ContactController::form',
                'routeName' => 'app_contact',
                'seo' => [
                    'title' => 'Contact',
                    'description' => ''
                ]
            ],
            [
                'title' => 'Mentions légales',
                'path' => 'mentions-legals',
                'type' => PageTypeEnum::INTERNAL_PAGE,
                'controller' => 'App\Controller\App\Legal\LegalController::mentions',
                'routeName' => 'app_legal_mentions',
                'seo' => [
                    'title' => 'Mentions légales',
                    'description' => ''
                ]
            ],
        ];
    }
}
