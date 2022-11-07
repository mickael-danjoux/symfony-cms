<?php

namespace App\Enum;


enum MenuCategoryTypeEnum: string {
	case ITEM = 'ITEM';
	case INTERNAL_LINK = 'INTERNAL_LINK';
	case CUSTOM_LINK = 'CUSTOM_LINK';

	public static function getList(): array
	{
		return [
			'ITEM' => 'Élément du menu',
			'INTERNAL_LINK' => 'Lien interne',
			'CUSTOM_LINK' => 'Lien personnalisé',
		];
	}

	public function getLabel(): string
	{
		return self::getList()[$this->value];
	}
}
