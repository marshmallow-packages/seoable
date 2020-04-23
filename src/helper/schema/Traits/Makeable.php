<?php

namespace Marshmallow\Seoable\Helper\Schema\Traits;

trait Makeable
{
	protected $name;

	public static function make ($name)
	{
		$schema = new self;
		$schema->name = $name;
		return $schema;
	}
}