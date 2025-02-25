<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;

class SchemaAnswer extends Schema
{
    use Makeable;

    public function toArray()
    {
        return [
            '@type' => 'Answer',
            '@graph' => [
                '@type' => 'Answer',
                'text' => $this->name,
            ]
        ];
    }
}
