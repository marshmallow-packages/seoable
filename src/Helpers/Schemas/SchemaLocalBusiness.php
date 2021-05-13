<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;

class SchemaLocalBusiness extends Schema
{
    use Makeable;

    public $url;
    public $priceRange;
    public $telephone;

    public function url(string $url = null)
    {
        $this->url = $url;
        return $this;
    }

    public function priceRange(string $priceRange = null)
    {
        $this->priceRange = $priceRange;
        return $this;
    }

    public function telephone(string $telephone = null)
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function toArray()
    {
        return [
            '@context' => 'https://schema.org/',
            '@type' => $this->type,
            '@id' => $this->id,
            'name' => $this->name,
            'image' => $this->images,
            'address' => $this->getJsonSchema('address'),
            'geo' => $this->getJsonSchema('geo'),
            'url' => $this->url,
            'priceRange' => $this->priceRange,
            'telephone' => $this->telephone,
            'openingHoursSpecification' => $this->openingHoursSpecification,
        ];
    }
}
