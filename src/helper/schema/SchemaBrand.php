<?php

namespace Marshmallow\Seoable\Helper\Schema;

use Marshmallow\Seoable\Helper\Schema\Schema;
use Marshmallow\Seoable\Helper\Schema\Traits\Makeable;

class SchemaBrand extends Schema
{
	use Makeable;

	public function toJson ()
	{
		return [
			'@type' => 'Brand',
			'name' => $this->name,
		];
	}
}