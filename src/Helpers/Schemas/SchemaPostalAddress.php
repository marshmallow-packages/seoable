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

    /**
     * The magic __call would set an unused $street property, so
     * streetAddress silently never rendered.
     */
    public function street(?string $street)
    {
        $this->address = $street;

        return $this;
    }

    public function toArray()
    {
        return [
                '@type' => 'PostalAddress',
                'streetAddress' => $this->address,
                'addressLocality' => $this->locality,
                'addressRegion' => $this->region,
                'postalCode' => $this->postalCode,
                'addressCountry' => $this->country,
        ];
    }
}
