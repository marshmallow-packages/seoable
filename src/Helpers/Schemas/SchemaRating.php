<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Schema;

class SchemaRating extends Schema
{
	protected $worstRating = 0;
	protected $bestRating = 5;

	public static function make(float $ratingValue)
	{
		$schema = new self;
		$schema->ratingValue = $ratingValue;
		return $schema;
	}

	public function toJson()
	{
		return [
			'@type' => 'Rating',
			'bestRating' => $this->bestRating,
			'ratingValue' => $this->ratingValue,
			'worstRating' => $this->worstRating,
		];
	}
}
