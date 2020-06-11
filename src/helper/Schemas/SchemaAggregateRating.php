<?php

namespace Marshmallow\Seoable\Helper\Schemas;

use Marshmallow\Seoable\Helper\Schemas\Schema;
use Marshmallow\Seoable\Helper\Schemas\Traits\Makeable;

class SchemaAggregateRating extends Schema
{
	public static function make (float $ratingValue, float $reviewCount)
	{
		$schema = new self;
		$schema->ratingValue = $ratingValue;
		$schema->reviewCount = $reviewCount;
		return $schema;
	}

	public function toJson ()
	{
		return [
			'@type' => 'AggregateRating',
			'ratingValue' => $this->ratingValue,
			'reviewCount' => $this->reviewCount,
		];
	}
}
