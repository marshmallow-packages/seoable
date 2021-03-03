<?php

namespace Marshmallow\Seoable\Traits;

trait SeoSitemapTrait
{
    abstract public function getSitemapItemUrl(): string;

    public function getSitemapItemLastModified()
    {
        if (isset($this->updated_at) || isset($this->created_at)) {
            return isset($this->updated_at) ? $this->updated_at : $this->created_at;
        }

        return null;
    }

    public function showItemInSitemap()
    {
        return true;
    }

    abstract public static function getSitemapItems();
}
