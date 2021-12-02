<?php

namespace Marshmallow\Seoable\Traits;

trait SeoSitemapTrait
{
    abstract public function getSitemapItemUrl(): string;

    public function getSitemapItemLastModified()
    {
        if (isset($this->updated_at) || isset($this->created_at)) {

            $date_format = config('seo.sitemap_date_format') ?? 'Y-m-d\TH:i:s.u\Z';

            return isset($this->updated_at)
                ? $this->updated_at->format($date_format)
                : $this->created_at->format($date_format);
        }

        return null;
    }

    public function showItemInSitemap()
    {
        return true;
    }

    abstract public static function getSitemapItems();
}
