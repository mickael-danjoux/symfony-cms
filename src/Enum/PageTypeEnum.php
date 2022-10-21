<?php

namespace App\Enum;

use JetBrains\PhpStorm\ArrayShape;

enum PageTypeEnum: string
{
    case INTERNAL_PAGE = 'INTERNAL_PAGE';
    case CUSTOM_PAGE = 'CUSTOM_PAGE';

    #[ArrayShape(['Page interne' => "\App\Enum\PageTypeEnum", 'Page custom' => "\App\Enum\PageTypeEnum"])] static function choicesForForm(): array
    {
        return [
            'Page interne' => PageTypeEnum::INTERNAL_PAGE,
            'Page custom' => PageTypeEnum::CUSTOM_PAGE
        ];
    }
}
