<?php

namespace App\Factories\Menu;

use App\Entity\Category\Category;
use App\Enum\MenuCategoryTypeEnum;

class DefaultMenuArray
{
    static function getList(): array
    {
        return [
            [
                'title' => 'Accueil',
                'sort' => 1,
                'type' => MenuCategoryTypeEnum::INTERNAL_LINK,
                'pagePath' => '/'
            ],
            [
                'title' => 'Contact',
                'sort' => 1,
                'type' => MenuCategoryTypeEnum::INTERNAL_LINK,
                'pagePath' => 'contact'
            ]
        ];
    }
}
