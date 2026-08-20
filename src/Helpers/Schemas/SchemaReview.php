<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;

class SchemaReview extends Schema
{
    use Makeable;

    protected $author;
    protected $ratingValue;

    public function author($author)
    {
        $this->author = $author;

        return $this;
    }

    public function ratingValue($ratingValue)
    {
        $this->ratingValue = $ratingValue;

        return $this;
    }

    public function toJson()
    {
        $data = [
            '@type' => 'Review',
            'datePublished' => $this->datePublished,
            'name' => $this->name,
        ];

        if ($this->author) {
            $data['author'] = [
                '@type' => 'Person',
                'name' => $this->author,
            ];
        }

        if ($this->ratingValue !== null) {
            $data['reviewRating'] = SchemaRating::make((float) $this->ratingValue)->toJson();
        }

        return $data;
    }
}
