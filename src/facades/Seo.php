<?php 

namespace Marshmallow\Seoable\Facades;

/**
 */
class Seo extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return \Marshmallow\Seoable\Seo::class;
    }
}