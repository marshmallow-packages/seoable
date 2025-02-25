<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;

class SchemaQuestion extends Schema
{
    use Makeable;

    protected $answer;

    public function addAnswer(SchemaAnswer $answer)
    {
        $this->answer = $answer;

        return $this;
    }

    public function toArray()
    {
        return [
            '@type' => 'Question',
            'name' => $this->name,
            'acceptedAnswer' => $this->answer->toArray(),
        ];
    }
}
