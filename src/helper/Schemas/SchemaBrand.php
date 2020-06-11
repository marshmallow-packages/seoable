<?php

namespace Marshmallow\Seoable\Helper\Schemas;

use Marshmallow\Seoable\Helper\Schemas\Schema;
use Marshmallow\Seoable\Helper\Schemas\Traits\Makeable;

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
