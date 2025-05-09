<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Carbon\Carbon;
use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;

class SchemaVideoObject extends Schema
{
    use Makeable;

    protected $thumbnailUrl = [];

    protected $contentUrl;

    protected $embedUrl;

    protected $uploadDate;

    protected $duration;

    protected $interactionStatistic;

    protected $expires;

    public function thumbnail(?string $public_path = null)
    {
        if ($public_path) {
            $this->thumbnailUrl[] = $public_path;
        }

        return $this;
    }

    public function contentUrl(?string $video_url = null)
    {
        if ($video_url) {
            $this->contentUrl = $video_url;
        }

        return $this;
    }

    public function embedUrl(?string $embed_url = null)
    {
        if ($embed_url) {
            $this->embedUrl = $embed_url;
        }

        return $this;
    }

    public function uploadDate(?Carbon $date = null)
    {
        if ($date) {
            $this->uploadDate = $date;
        }

        return $this;
    }

    public function duration(?int $seconds = null)
    {
        return $this->getDurationStringFromSeconds('duration', $seconds);
    }

    public function expires(?Carbon $date = null)
    {
        if ($date) {
            $this->expires = $date;
        }

        return $this;
    }

    public function toJson()
    {
        return [
            '@type' => 'VideoObject',
            '@graph' => [
                '@type' => 'VideoObject',
                'name' => $this->name,
                'description' => $this->description,
                'thumbnailUrl' => $this->thumbnailUrl,
                'contentUrl' => $this->contentUrl,
                'embedUrl' => $this->embedUrl,
                'uploadDate' => $this->uploadDate,
                'duration' => $this->duration,
                'interactionStatistic' => $this->getJsonSchema('interactionStatistic'),
                'expires' => $this->expires,
            ]
        ];
    }
}
