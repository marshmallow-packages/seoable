<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;

class SchemaGeoCoordinates extends Schema
{
    use Makeable;

    public $latitude;
    public $longitude;

    public function toArray()
    {
        return [
            '@type' => 'GeoCoordinates',
            '@graph' => [
                '@type' => 'GeoCoordinates',
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ]
        ];
    }
}
