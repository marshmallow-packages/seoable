<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Carbon\Carbon;

class Schema
{
    protected $brand;

    protected $rating;

    protected $aggregateRating;

    protected $interactionStatistic;

    protected $datePublished;

    protected $description;

    protected $video;

    protected $offers = [];

    protected $images = [];

    protected $reviews = [];

    public function brand(string $name = null)
    {
        if ($name) {
            $this->brand = SchemaBrand::make($name)->toJson();
        }

        return $this;
    }

    public function offer(SchemaOffer $offer)
    {
        $this->offers([$offer]);

        return $this;
    }

    public function offers(array $offers = [])
    {
        if (!$offers) {
            return $this;
        }

        foreach ($offers as $offer) {
            if (!$offer instanceof SchemaOffer) {
                continue;
            }
            $this->offers[] = $offer->toJson();
        }

        return $this;
    }

    public function rating(SchemaRating $rating)
    {
        $this->rating = $rating;

        return $this;
    }

    public function review(SchemaReview $review)
    {
        $this->reviews[] = $review->toJson();

        return $this;
    }

    public function reviews(array $reviews = [])
    {
        if ($reviews) {
            foreach ($reviews as $review) {
                $this->review($review);
            }
        }

        return $this;
    }

    public function aggregateRating(SchemaAggregateRating $aggregateRating)
    {
        $this->aggregateRating = $aggregateRating->toJson();

        return $this;
    }

    public function image(string $image = null)
    {
        if ($image) {
            $this->images[] = $image;
        }

        return $this;
    }

    public function images(array $images = [])
    {
        if ($images) {
            $this->images = array_merge($this->images, $images);
        }

        return $this;
    }

    public function datePublished(Carbon $date = null)
    {
        if ($date) {
            $this->datePublished = $date;
        }

        return $this;
    }

    public function description(string $description = null)
    {
        if ($description) {
            $this->description = $description;
        }

        return $this;
    }

    public function video(SchemaVideoObject $video)
    {
        $this->video = $video;

        return $this;
    }

    public function interactionStatistic(SchemaInteractionCounter $counter)
    {
        $this->interactionStatistic = $counter;

        return $this;
    }

    protected function getDurationStringFromSeconds(string $column, int $seconds = null)
    {
        if (!$seconds) {
            return $this;
        }

        $time = gmdate('H:i:s', $seconds);
        $parts = collect(explode(':', $time))->map(function ($part) {
            return intval($part);
        })->toArray();

        $duration = 'PT';
        $duration .= $parts[0].'H';
        $duration .= $parts[1].'M';
        $duration .= $parts[2].'S';

        $this->{$column} = $duration;

        return $this;
    }

    protected function getJsonSchema($column)
    {
        if (!$this->{$column}) {
            return null;
        }
        $data = $this->{$column}->toJson();

        return array_filter($data);
    }

    public function toJson()
    {
        $array = $this->toArray();
        $array = array_filter($array);

        return $array;
    }

    public function __call($parameter, $arguments)
    {
        $this->{$parameter} = $arguments[0];

        return $this;
    }

    public function __toString()
    {
        return json_encode($this->toJson());
    }
}
