<?php

namespace App\Enum;

enum PageTypeEnum: string
{
    case INTERNAL_PAGE = 'INTERNAL_PAGE';
    case CUSTOM_PAGE = 'CUSTOM_PAGE';

	public static function getList(): array
	{
		return [
			'INTERNAL_PAGE' => 'Page interne',
			'CUSTOM_PAGE' => 'Page custom',
		];
	}

	public function getLabel(): string
	{
		return self::getList()[$this->value];
	}

}
