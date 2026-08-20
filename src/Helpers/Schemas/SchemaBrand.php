<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;

class SchemaBrand extends Schema
{
    use Makeable;

    public function toJson()
    {
        return [
            '@type' => 'Brand',
            'name' => $this->name,
        ];
    }
}
