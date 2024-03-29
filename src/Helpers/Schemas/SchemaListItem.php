<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;

class SchemaListItem extends Schema
{
    use Makeable;

    protected $item;
    protected $position;

    public function position($position = null)
    {
        if ($position) {
            $this->position = $position;
        }

        return $this;
    }

    public function url($url = null)
    {
        if ($url) {
            $this->item = $url;
        }

        return $this;
    }

    public function toJson()
    {
        $array = [
            'name' => $this->name,
        ];

        if (isset($this->item) && $this->item) {
            $array['@id'] = $this->item;
        }

        return [
            '@type' => 'ListItem',
            'position' => $this->position,
            'item' => $array,
        ];
    }
}
