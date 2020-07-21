<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Schema;
use Marshmallow\Seoable\Helpers\Schemas\SchemaListItem;

class SchemaBreadcrumbList extends Schema
{
	protected $itemListElement = [];

	public static function make()
	{
		return new self;
	}

	public function addItems(array $items)
	{
		foreach ($items as $item) {
			$this->addItem($item);
		}
		return $this;
	}

	public function addItem(SchemaListItem $item)
	{
		$position = count($this->itemListElement) + 1;
		$this->itemListElement[] = $item->position($position)->toJson();
	}

	public function toJson()
	{
		return [
			'@context' => 'https://schema.org',
			'@type' => 'BreadcrumbList',
			'itemListElement' => $this->itemListElement,
		];
	}
}