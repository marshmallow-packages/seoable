<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

class SchemaRating extends Schema
{
    protected $worstRating = 0;
    protected $bestRating = 5;
    protected $ratingValue;

    public static function make(float $ratingValue)
    {
        $schema = new self();
        $schema->ratingValue = $ratingValue;

        return $schema;
    }

    public function toJson()
    {
        return [
            '@type' => 'Rating',
            '@graph' => [
                '@type' => 'Rating',
                'bestRating' => $this->bestRating,
                'ratingValue' => $this->ratingValue,
                'worstRating' => $this->worstRating,
            ]
        ];
    }
}
