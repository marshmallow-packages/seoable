<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;

class SchemaProduct extends Schema
{
    use Makeable;

    public $sku;
    public $mpn;
    public $gtin;
    public $isbn;
    public $brand;
    public $offers;
    public $images;
    public $reviews;
    public $description;
    public $aggregateRating;

    public function sku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    public function mpn($mpn)
    {
        $this->mpn = $mpn;

        return $this;
    }

    public function gtin($gtin)
    {
        $this->gtin = $gtin;

        return $this;
    }

    public function isbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function toArray()
    {
        return [
            '@context' => 'https://schema.org/',
            '@graph' => [
                '@type' => 'Product',
                'name' => $this->name,
                'image' => $this->images,
                'description' => $this->description,
                'brand' => $this->brand,
                'offers' => $this->offers,
                'sku' => $this->sku,
                'mpn' => $this->mpn,
                'gtin' => $this->gtin,
                'isbn' => $this->isbn,
                'aggregateRating' => $this->aggregateRating,
                'review' => $this->reviews,
            ],
        ];
    }
}
