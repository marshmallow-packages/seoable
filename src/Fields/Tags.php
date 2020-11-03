<?php

namespace Marshmallow\Seoable\Fields;

use Laravel\Nova\Http\Requests\NovaRequest;

class Tags extends \Spatie\TagsField\Tags
{
    protected static $separator = '-----';

    public static function stringToArray(string $string)
    {
        $tag_names = explode(self::$separator, $string);

        return array_filter($tag_names);
    }

    public static function arrayToString(array $tag_names)
    {
        $tag_names = array_filter($tag_names);

        return join(self::$separator, $tag_names);
    }

    /**
     * Show!!!
     *
     * @param [type] $resource  [description]
     * @param [type] $attribute [description]
     *
     * @return [type] [description]
     */
    public function resolveAttribute($resource, $attribute = null)
    {
        return app('seo')->set($resource)->getSeoKeywords();
    }

    /**
     * STORE!!!!
     *
     * @param NovaRequest $request          [description]
     * @param [type]      $requestAttribute [description]
     * @param [type]      $model            [description]
     * @param [type]      $attribute        [description]
     *
     * @return [type] [description]
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $request->$requestAttribute = Tags::stringToArray($request->$requestAttribute);
        app('seo')->set($model)->store($request, $attribute, 'keywords');
    }
}
