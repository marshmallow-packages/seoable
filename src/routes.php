<?php

use Marshmallow\Seoable\Helper\SeoSitemap;

if(config('seo.sitemap_status')){
    Route::get(config('seo.sitemap_path'), function(){
        $sitemap = new SeoSitemap;

        return response($sitemap->toXml(), 200, [
            'Content-Type' => 'application/xml'
        ]);
    });
}
