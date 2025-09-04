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
            'name' => $this->name,
        ];

        if (config('seo.defaults.logo')) {
            $data['logo'] = config('seo.defaults.logo');
        }

        if (config('seo.defaults.description')) {
            $data['description'] = config('seo.defaults.description');
        }

        if (config('seo.defaults.email')) {
            $data['email'] = config('seo.defaults.email');
        }

        if (config('seo.defaults.phonenumber')) {
            $data['telephone'] = config('seo.defaults.phonenumber');
        }

        if (config('seo.defaults.vat_id')) {
            $data['vatID'] = config('seo.defaults.vat_id');
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => $data,
        ];
    }
}
