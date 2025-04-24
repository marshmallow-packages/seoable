<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;

class SchemaHowToStep extends Schema
{
    use Makeable;

    protected $text;

    protected $url;

    protected $image;

    public function image(?string $public_path = null)
    {
        if ($public_path) {
            $this->image = $public_path;
        }

        return $this;
    }

    public function toJson()
    {
        return [
            '@type' => 'HowToStep',
            '@graph' => [
                '@type' => 'HowToStep',
                'name' => $this->name,
                'text' => $this->text,
                'url' => $this->url,
                'image' => $this->image,
            ]
        ];
    }
}
