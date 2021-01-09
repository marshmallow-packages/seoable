<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;

class SchemaHowToStep extends Schema
{
    use Makeable;

    protected $text;

    protected $url;

    protected $image;

    public function image(string $image)
    {
        $this->image = $image;

        return $this;
    }

    public function toJson()
    {
        return [
            '@type' => 'HowToStep',
            'name' => $this->name,
            'text' => $this->text,
            'url' => $this->url,
            'image' => $this->image,
        ];
    }
}
