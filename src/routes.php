<?php

use Illuminate\Support\Facades\Route;
use Marshmallow\Seoable\Helpers\SeoSitemap;
use Marshmallow\Seoable\Http\Controllers\RobotsController;

if (config('seo.sitemap_status')) {
    Route::get(config('seo.sitemap_path'), function () {
        $sitemap = new SeoSitemap();

        return response($sitemap->toXml(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    });
}

Route::get('robots.txt', [RobotsController::class, 'render']);
