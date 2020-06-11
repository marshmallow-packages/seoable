<?php

namespace Marshmallow\Seoable\Helper\Schemas;

use Marshmallow\Seoable\Helper\Schemas\Schema;
use Marshmallow\Seoable\Helper\Schemas\SchemaListItem;
use Marshmallow\Seoable\Helper\Schemas\Traits\Makeable;

class SchemaBreadcrumbList extends Schema
{
	protected $itemListElement = [];

	public static function make ()
	{
		return new self;
	}

	public function addItems (array $items)
	{
		foreach ($items as $item) {
			$this->addItem($item);
		}
		return $this;
	}

	public function addItem (SchemaListItem $item)
	{
		$position = count($this->itemListElement) + 1;
		$this->itemListElement[] = $item->position($position)->toJson();
	}

	public function toJson ()
	{
		return [
			'@context' => 'https://schema.org',
			'@type' => 'BreadcrumbList',
			'itemListElement' => $this->itemListElement,
		];
	}
}
