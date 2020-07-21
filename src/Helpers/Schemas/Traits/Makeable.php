<?php

namespace Marshmallow\Seoable\Helpers\Schemas\Traits;

trait Makeable
{
	protected $name;

	public static function make($name)
	{
		$schema = new self;
		$schema->name = $name;
		return $schema;
	}
}
