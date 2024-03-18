<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;

class SchemaOrganization extends Schema
{
    use Makeable;

    public function toArray()
    {
        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                '@type' => 'Organization',
                'url' => config('app.url'),
                'logo' => $this->name,
            ],
        ];
    }
}
