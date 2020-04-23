<?php

namespace Marshmallow\Seoable\Traits;

use Marshmallow\Seoable\Facades\Seo;
use Illuminate\Database\Eloquent\Model;
use Marshmallow\Seoable\Models\SeoableItem;

trait Seoable
{
	public function useForSeo ()
	{
		Seo::set($this);
		return $this;
	}
	/**
     * Get SEO title formatter
     *
     * @return
     */
    public function getSeoTitleFormatter()
    {
        return config('seo.title_formatter');
    }

	public function setSeoTitle (): ?string
	{
		return $this->name;
	}

	public function getSeoTitle ()
	{
		return 'Test title';
	}

	public function setSeoDescription (): ?string
	{
		return $this->description;
	}

	public function setSeoKeywords (): ?array
	{
		return [];
	}

	public function setSeoImage (): ?string
	{
		return $this->image;
	}

	public function setSeoFollowType (): ?string
	{
		return null;
	}

	public function setSeoableImageAttribute($value)
    {
        //
    }

	public function seoable()
    {
        return $this->morphOne(SeoableItem::class, 'seoable');
    }
}