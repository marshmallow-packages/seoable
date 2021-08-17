<?php

namespace Marshmallow\Seoable\Helpers\Schemas\Traits;

trait Makeable
{
    protected $name;

    public static function make($name = null)
    {
        $schema = new self();
        $schema->name = $name;

        return $schema;
    }
}
