<?php

namespace Marshmallow\Seoable\Helpers;

use Error;
use Carbon\Carbon;
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
     * Cache of canonical pretty URLs to avoid N+1 queries.
     *
     * @var array|null
     */
    private $canonicalPrettyUrls = null;

    /**
     * Construct the sitemap class.
     *
     * @return void
     */
    public function __construct()
    {
        $sitemap_models = config('seo.sitemap_models');

        $this->attachModelItems($sitemap_models);
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
                // Pre-load canonical pretty URLs to avoid N+1 queries
                $this->preloadCanonicalPrettyUrls($items);

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

                // Clear cache after processing each model
                $this->canonicalPrettyUrls = null;
            }
        }
    }

    /**
     * Pre-load all canonical pretty URLs for the given items in a single query.
     *
     * @param \Illuminate\Support\Collection $items
     * @return void
     */
    protected function preloadCanonicalPrettyUrls($items)
    {
        try {
            // Collect all URLs from items
            $urls = $items->map(function ($item) {
                try {
                    return url($item->getSitemapItemUrl());
                } catch (Error $e) {
                    return null;
                }
            })->filter()->unique()->values()->toArray();

            if (empty($urls)) {
                $this->canonicalPrettyUrls = [];
                return;
            }

            // Fetch all canonical pretty URLs in one query
            $this->canonicalPrettyUrls = Seo::$prettyUrlModel::whereIn('original_url', $urls)
                ->where('is_canonical', true)
                ->pluck('original_url')
                ->flip()
                ->toArray();
        } catch (Error $e) {
            $this->canonicalPrettyUrls = [];
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
     * If there is a canonical version of the URL available then we should
     * not load this route in the sitemap because it would be a duplicate url.
     * We magicaly load in all the pretty urls.
     */
    protected function hasCanonicalPrettyUrl($item)
    {
        try {
            $url = url($item->getSitemapItemUrl());

            // Use pre-loaded cache if available (O(1) lookup)
            if ($this->canonicalPrettyUrls !== null) {
                return isset($this->canonicalPrettyUrls[$url]);
            }

            // Fallback to individual query if cache not loaded
            $pretty_url = Seo::$prettyUrlModel::where('original_url', $url)->first();
            return $pretty_url && $pretty_url->is_canonical;
        } catch (Error $e) {
            return false;
        }
    }

    /**
     * Attach a custom sitemap item.
     *
     * @param string $path    Path on the current site
     * @param Carbon $lastmod Date of last edit
     *
     * @return SeoSitemap
     */
    public function attachCustom($path, ?Carbon $lastmod = null)
    {
        $date_format = config('seo.sitemap_date_format') ?? 'Y-m-d\TH:i:s.u\Z';

        $lastmod = $lastmod ? $lastmod->format($date_format) : null;

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
