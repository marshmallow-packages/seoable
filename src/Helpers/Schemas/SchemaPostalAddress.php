<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;

class SchemaPostalAddress extends Schema
{
    use Makeable;

    public $address;
    public $locality;
    public $region;
    public $postalCode;
    public $country;

    public function toArray()
    {
        return [
            '@type' => 'PostalAddress',
            '@graph' => [
                'streetAddress' => $this->address,
                'addressLocality' => $this->locality,
                'addressRegion' => $this->region,
                'postalCode' => $this->postalCode,
                'addressCountry' => $this->country,
            ]
        ];
    }
}
