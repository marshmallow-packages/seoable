<?php

namespace Marshmallow\SeoMeta\Traits;

use Marshmallow\SeoMeta\Models\SeoMetaItem;
use Illuminate\Support\Facades\Storage;

trait SeoMetaTrait
{
    /**
     * Get the seo_metaable relationship.
     *
     * @return morphOne
     */
    public function seo_meta()
    {
        return $this->morphOne(SeoMetaItem::class, 'seo_metaable');
    }

    /**
     * Return the seo_metaable data as array
     *
     * @return array
     */
    public function getSeoMeta()
    {
        $attrs = false;

        if ($this->seo_meta) {
            $attrs = $this->seo_meta->toArray();
        } else {
            $title = $this->getSeoTitleDefault();

            if ($title) {
                $formatter = $this->getSeoTitleFormatter() ?? config('seo.title_formatter');
                $attrs = [
                    'title' => $title,
                    'description' => $this->getSeoDescriptionDefault(),
                    'keywords' => $this->getSeoKeywordsDefault(),
                    'image' => $this->getSeoImageDefault(),
                    'follow_type' => $this->getSeoFollowDefault(),
                    'params' => (object)[
                        'title_format' => $formatter
                    ]
                ];
            }
        }

        if($attrs && isset($attrs['image']) && $attrs['image']){
            $attrs['image_path'] = Storage::url($attrs['image']);
        }

        return $attrs;
    }

    protected function parseSeoData ($seo_string_to_parse)
    {
        preg_match_all('/\{(.*?)\}/', $seo_string_to_parse, $matches);
        if ($matches) {
            $searches = [];
            foreach ($matches[0] as $k => $match) {
                $searches[$match] = $matches[1][$k];
            }
            foreach ($searches as $find => $replace) {
                $model_info = explode('.', $replace);
                $model_class = '\App\Models\\' . $model_info[0];
                $request_paramaters = strtolower(substr($model_info[0], 0, 1)) . substr($model_info[0], 1, strlen($model_info[0]));
                $model = $model_class::where('slug', request()->{$request_paramaters})->get()->first();

                $paramater = $model_info[1];
                if (strpos($paramater, '()')) {
                    $paramater = str_replace('()', '', $paramater);
                    $value = $model->{$paramater}();
                } else {
                    $value = $model->{$paramater};
                }

                $seo_string_to_parse = str_replace($find, $value, $seo_string_to_parse);
            }
        }
        return $seo_string_to_parse;
    }
    
    public function setSeoParameters ()
    {
        $seo = $this->seo_meta;

        if ($seo) {
            if ($seo->title) {
                SEOTools::setTitle(
                    $this->parseSeoData($seo->title)
                );
            }
            if ($seo->description) {
                SEOTools::setDescription(
                    $this->parseSeoData($seo->description)
                );
            }
            if ($seo->keywords) {
                SEOMeta::setKeywords(
                    $this->parseSeoData($seo->keywords)
                );
            }
            if ($seo->follow_type) {
                
            }
            if ($seo->image) {
                SEOTools::addImages('storage/' . $seo->image);
            }
        }
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

    /**
     * Get default SEO title
     *
     * @return string
     */
    public function getSeoTitleDefault()
    {
        return null;
    }

    /**
     * Get default SEO description
     *
     * @return string
     */
    public function getSeoDescriptionDefault()
    {
        return null;
    }

    /**
     * Get default SEO title
     *
     * @return string
     */
    public function getSeoKeywordsDefault()
    {
        return null;
    }

    /**
     * Get default SEO title
     *
     * @return string
     */
    public function getSeoImageDefault()
    {
        return null;
    }

    /**
     * Get default SEO title
     *
     * @return string
     */
    public function getSeoFollowDefault()
    {
        return config('seo.default_follow_type');
    }
}
