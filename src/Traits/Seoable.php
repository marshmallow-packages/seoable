<?php

namespace Marshmallow\Seoable\Traits;

use Marshmallow\Seoable\Facades\Seo;
use Illuminate\Database\Eloquent\Model;
use Marshmallow\Seoable\Models\SeoableItem;

trait Seoable
{
    public static function bootSeoable()
    {
        static::created(function (Model $resource) {
            if ($resource->shouldStoreRecordOnInsert()) {
                $resource->seoable()->create([
                    'title' => app('seo')->set($resource)->getSeoTitle(),
                    'description' => app('seo')->set($resource)->getSeoDescription(),
                    'keywords' => app('seo')->set($resource)->getSeoKeywords(),
                    'follow_type' => app('seo')->set($resource)->getSeoFollowType(),
                    'image' => app('seo')->set($resource)->getSeoImageUrl(),
                    'page_type' => app('seo')->set($resource)->getSeoPageType(),
                ]);
            }
        });

        static::deleting(function (Model $resource) {
            /*
             * Delete the existing seoable information.
             */
            $resource->seoable()->delete();
        });
    }

    public function shouldStoreRecordOnInsert()
    {
        /*
         * If this resource is translatable, we need to always create
         * a SEO record so this can be translated. It translations are
         * not available, we will only create a record when it differs
         * from the default settings.
         */
        if (method_exists($this, 'bootTranslatable')) {
            return true;
        }

        return false;
    }

    public function useForSeo()
    {
        Seo::set($this);

        return $this;
    }

    /**
     * Get SEO title formatter.
     *
     * @return
     */
    public function getSeoTitleFormatter()
    {
        return config('seo.title_formatter');
    }

    public function setSeoTitle(): ?string
    {
        return $this->name;
    }

    public function getSeoTitle()
    {
        return 'Test title';
    }

    public function setSeoDescription(): ?string
    {
        return $this->description;
    }

    public function setSeoKeywords(): ?array
    {
        return [];
    }

    public function setSeoImage(): ?string
    {
        return $this->image;
    }

    public function setSeoFollowType(): ?string
    {
        return null;
    }

    public function setSeoableImageAttribute($value)
    {
    }

    public function seoable()
    {
        return $this->morphOne(SeoableItem::class, 'seoable');
    }
}
