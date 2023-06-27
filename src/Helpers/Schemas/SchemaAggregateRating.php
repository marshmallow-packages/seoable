<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

class SchemaAggregateRating extends Schema
{
    protected $name;

    protected $bestRating;

    protected $worstRating;

    protected $ratingValue;

    protected $reviewCount;

    public static function make(float $ratingValue, float $reviewCount)
    {
        $schema = new self();
        $schema->ratingValue = $ratingValue;
        $schema->reviewCount = $reviewCount;

        return $schema;
    }

    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function toJson()
    {
        $data = [
            '@type' => 'AggregateRating',
            'ratingValue' => $this->ratingValue,
            'reviewCount' => $this->reviewCount,
            'bestRating' => $this->bestRating,
            'worstRating' => $this->worstRating,
        ];

        if ($this->name) {
            $data['name'] = $this->name;
        }

        return $data;
    }
}
