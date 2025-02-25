<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;

class SchemaOpeningHoursSpecification extends Schema
{
    use Makeable;

    public $latitude;
    public $longitude;
    public $dayOfWeek;
    public $opens;
    public $closes;

    public function toArray()
    {
        return [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => $this->dayOfWeek,
            'opens' => $this->opens,
            'closes' => $this->closes,
        ];
    }
}
