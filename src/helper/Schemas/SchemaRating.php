<?php

namespace Marshmallow\Seoable\Helper\Schemas;

use Marshmallow\Seoable\Helper\Schemas\Schema;
use Marshmallow\Seoable\Helper\Schemas\Traits\Makeable;

class SchemaRating extends Schema
{
	protected $worstRating = 0;
	protected $bestRating = 5;

	public static function make (float $ratingValue)
	{
		$schema = new self;
		$schema->ratingValue = $ratingValue;
		return $schema;
	}

	public function toJson ()
	{
		return [
			'@type' => 'Rating',
			'bestRating' => $this->bestRating,
			'ratingValue' => $this->ratingValue,
			'worstRating' => $this->worstRating,
		];
	}
}
