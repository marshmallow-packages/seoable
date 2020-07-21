<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Schema;

class SchemaAggregateRating extends Schema
{
	public static function make(float $ratingValue, float $reviewCount)
	{
		$schema = new self;
		$schema->ratingValue = $ratingValue;
		$schema->reviewCount = $reviewCount;
		return $schema;
	}

	public function toJson()
	{
		return [
			'@type' => 'AggregateRating',
			'ratingValue' => $this->ratingValue,
			'reviewCount' => $this->reviewCount,
		];
	}
}
