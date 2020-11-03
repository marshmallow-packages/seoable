<?php

namespace Marshmallow\Seoable\Facades;

class Seo extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return \Marshmallow\Seoable\Seo::class;
    }
}
