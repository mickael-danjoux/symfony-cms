<?php

namespace App\Services\Menu;
use App\Entity\Category\MenuCategory;
use App\Enum\MenuCategoryTypeEnum;

class MenuService
{
    public static function clearFields(MenuCategory $menuCategory): void
    {
        match ($menuCategory->getType()) {
            MenuCategoryTypeEnum::INTERNAL_LINK => self::resetCustomLinkFields($menuCategory),
            MenuCategoryTypeEnum::ITEM => self::resetCustomLinkAndPageFields($menuCategory),
            MenuCategoryTypeEnum::CUSTOM_LINK => self::resetPageFields($menuCategory)
        };
    }
    private static function resetCustomLinkFields(MenuCategory $menuCategory): void
    {
        $menuCategory->setUrl(null);
        $menuCategory->setNewTab(false);
    }
    private static function resetPageFields(MenuCategory $menuCategory): void
    {
//        $menuCategory->setPage(null);
    }
    private static function resetCustomLinkAndPageFields(MenuCategory $menuCategory): void
    {
        self::resetCustomLinkFields($menuCategory);
        self::resetPageFields($menuCategory);
    }
}
