<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

class SchemaInteractionCounter extends Schema
{
    protected $type;

    protected $count;

    public static function make(string $type, int $count)
    {
        $counter = new self();

        return $counter->type($type)
                       ->count($count);
    }

    public function toJson()
    {
        return [
            '@type' => 'InteractionCounter',
            'interactionType' => [
                '@type' => 'http://schema.org/'.$this->type,
            ],
            'userInteractionCount' => $this->count,
        ];
    }
}
