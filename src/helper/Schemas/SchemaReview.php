<?php

namespace Marshmallow\Seoable\Helper\Schemas;

use Marshmallow\Seoable\Helper\Schemas\Schema;
use Marshmallow\Seoable\Helper\Schemas\SchemaRating;
use Marshmallow\Seoable\Helper\Schemas\Traits\Makeable;

class SchemaReview extends Schema
{
	use Makeable;

	public function author ($author)
	{
		$this->author = $author;
		return $this;
	}

	public function ratingValue ($ratingValue)
	{
		$this->ratingValue = $ratingValue;
		return $this;
	}

	public function toJson ()
	{
		return [
			'@type' => 'Review',
			'author' => $this->author,
			'datePublished' => $this->datePublished,
			'name' => $this->name,
			'reviewRating' => $this->rating(
				SchemaRating::make($this->ratingValue)
			),
		];
	}
}
