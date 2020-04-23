<?php

namespace Marshmallow\Seoable\Helper\Schema;

use Marshmallow\Seoable\Helper\Schema\Schema;
use Marshmallow\Seoable\Helper\Schema\Traits\Makeable;

class SchemaListItem extends Schema
{
	use Makeable;

	public function position ($position)
	{
		$this->position = $position;
		return $this;
	}

	public function url ($url)
	{
		$this->item = $url;
		return $this;
	}

	public function toJson ()
	{
		$array = [
			'@type' => 'ListItem',
			'position' => $this->position,
			'name' => $this->name,
		];

		if (isset($this->item) && $this->item) {
			$array['item'] = $this->item;
		}

		return $array;
	}
}