<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Carbon\Carbon;

class Schema
{
    public function brand(string $name)
    {
        $this->brand = SchemaBrand::make($name)->toJson();

        return $this;
    }

    public function offer(SchemaOffer $offer)
    {
        $this->offers([$offer]);

        return $this;
    }

    public function offers(array $offers)
    {
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

    public function reviews(array $reviews)
    {
        foreach ($reviews as $review) {
            $this->review($review);
        }

        return $this;
    }

    public function aggregateRating(SchemaAggregateRating $aggregateRating)
    {
        $this->aggregateRating = $aggregateRating->toJson();

        return $this;
    }

    public function image(string $image)
    {
        $this->images[] = $image;

        return $this;
    }

    public function images(array $images)
    {
        $this->images = array_merge($this->images, $images);

        return $this;
    }

    public function datePublished(Carbon $date)
    {
        $this->datePublished = $date;

        return $this;
    }

    public function description(string $description)
    {
        $this->description = $description;

        return $this;
    }

    public function toJson()
    {
        $array = $this->toArray();
        $array = array_filter($array);

        return $array;
    }
}
