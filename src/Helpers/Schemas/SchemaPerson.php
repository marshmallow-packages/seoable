<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;

class SchemaPerson extends Schema
{
    use Makeable;

    public function toArray()
    {
        return [
            '@type' => 'Person',
            '@graph' => [
                '@type' => 'Person',
                'name' => $this->name,
            ]
        ];
    }
}
