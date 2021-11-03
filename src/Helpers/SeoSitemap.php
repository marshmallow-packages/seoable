<?php

namespace Marshmallow\Seoable\Helpers;

use Error;
use Marshmallow\Seoable\Seo;
use Marshmallow\Seoable\Models\PrettyUrl;

class SeoSitemap
{
    /**
     * Array of the all the items in the sitemap.
     *
     * @var array
     */
    private $items = [];

    /**
     * Construct the sitemap class.
     *
     * @return void
     */
    public function __construct()
    {
        $sitemap_models = config('seo.sitemap_models');

        $this->attachModelItems($sitemap_models);
        $this->attachPrettyUrls();
    }

    /**
     * Attach the model items.
     *
     * @return void
     */
    private function attachModelItems(array $sitemap_models = [])
    {
        foreach ($sitemap_models as $sitemap_model) {
            $items = $sitemap_model::getSitemapItems();

            if ($items && $items->count() > 0) {
                $this->items = array_merge($this->items, $items->reject(function ($item) {
                    if ($item->seoable && $item->seoable instanceof Seo::$seoableItemModel) {
                        if (strpos($item->seoable->follow_type, 'noindex') !== false) {
                            return true;
                        }
                    }

                    if (!$item->showItemInSitemap()) {
                        return true;
                    }

                    if ($this->shouldBeExcluded($item)) {
                        return true;
                    }

                    if ($this->hasCanonicalPrettyUrl($item)) {
                        return true;
                    }

                    return false;
                })->map(function ($item) {
                    return (object) [
                        'url' => $item->getSitemapItemUrl(),
                        'lastmod' => $item->getSitemapItemLastModified(),
                    ];
                })->toArray());
            }
        }
    }

    protected function shouldBeExcluded($item)
    {
        if ($exclude = $item->seoable?->hide_in_sitemap) {
            return $exclude;
        }
        return false;
    }

    /**
     * Attach the pretty url items.
     *
     * @return void
     */
    protected function attachPrettyUrls()
    {
        try {
            PrettyUrl::get()->each(function ($url) {
                $this->attachCustom($url->pretty_url);
            });
        } catch (Error $e) {
            //
        }
    }

    /**
     * If there is a canonical version of the URL available then we should
     * not load this route in the sitemap because it would be a duplicate url.
     * We magicaly load in all the pretty urls.
     */
    protected function hasCanonicalPrettyUrl($item)
    {
        try {
            $url = $item->getSitemapItemUrl();
            $pretty_url = PrettyUrl::where('original_url', url($url))->first();
            return $pretty_url && $pretty_url->is_canonical;
        } catch (Error $e) {
            return false;
        }
    }

    /**
     * Attach a custom sitemap item.
     *
     * @param string $path    Path on the current site
     * @param string $lastmod Date of last edit
     *
     * @return SeoSitemap
     */
    public function attachCustom($path, $lastmod = null)
    {
        $this->items[] = (object) [
            'url' => url($path),
            'lastmod' => $lastmod,
        ];

        return $this;
    }

    /**
     * Return sitemap items as array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * Return xml for sitemap items.
     *
     * @return string
     */
    public function toXml()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' .
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $lastmod = null;

        foreach ($this->items as $item) {
            $xml .= '<url>' .
                '<loc>' . ('/' == substr($item->url, 0, 1) ? url($item->url) : $item->url) . '</loc>' .
                '<lastmod>' . ($item->lastmod ?? $lastmod) . '</lastmod>' .
                '</url>';

            if ($item->lastmod) {
                $lastmod = $item->lastmod;
            }
        }
        $xml .= '</urlset>';

        return $xml;
    }
}
