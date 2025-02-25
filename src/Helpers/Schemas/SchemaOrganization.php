<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;

class SchemaOrganization extends Schema
{
    use Makeable;

    public function toArray()
    {
        $data = [
            '@type' => 'Organization',
            'url' => config('app.url'),
            'logo' => $this->name,
        ];
        if (config('seo.defaults.phonenumber')) {
            $data['telephone'] = config('seo.defaults.phonenumber');
        }

        return $data;
    }
}
