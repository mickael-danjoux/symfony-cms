<?php
namespace App\Enum;

use JetBrains\PhpStorm\ArrayShape;

enum MenuCategoryTypeEnum: string {
    case ITEM = 'ITEM';
    case INTERNAL_LINK = 'INTERNAL_LINK';
    case CUSTOM_LINK = 'CUSTOM_LINK';

    #[ArrayShape(['Élément du menu' => "\App\Enum\MenuCategoryTypeEnum", 'Lien interne' => "\App\Enum\MenuCategoryTypeEnum", 'Lien personnalisé' => "\App\Enum\MenuCategoryTypeEnum"])] static function choicesForForm(): array
    {
        return [
            'Élément du menu' => MenuCategoryTypeEnum::ITEM,
            'Lien interne' => MenuCategoryTypeEnum::INTERNAL_LINK,
            'Lien personnalisé' => MenuCategoryTypeEnum::CUSTOM_LINK
        ];
    }
}
