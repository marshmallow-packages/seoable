<?php

return [
    'defaults' => [
        'sitename' => env('APP_NAME'),
        'title' => env('APP_NAME') . ' from config',
        'description' => 'Description from config',
        'keywords' => [env('APP_NAME'), 'Marshmallow Alphen'],
        'image' => 'https://marshmallow.dev/cdn/media/mrmallow-250x250.png',
        'follow_type' => 'index, follow',
        'page_type' => 'website',
        'author' => 'Marshmallow',
        'logo' => null,
    ],

    'use_pretty_urls' => false,

    'google' => [
        'GTM' => env('SEO_GTM', ''),            // GTM-XXXXXXX
        'GA' => env('SEO_GA', ''),              // GA-XXXXXXX-XX
    ],

    'google_optimize' => [
        'container' => env('SEO_GO', ''),   // GTM-XXXXXXX

        /*
         * If you add Google Optimize via GTM then we need
         * to initiate it in a different way. We will use
         * the config google.GTM to initiate optimize, the
         * container id is not required then and won't be
         * used.
         */
        'via_gtm' => env('SEO_GO_VIA_GTM', false),
    ],

    'facebook' => [
        'admins' => env('FACEBOOK_ADMINS', ''),
        'app_id' => env('FACEBOOK_APP_ID', ''),
    ],

    'twitter' => [
        'site' => '',      // @marshmallowdev
        'creator' => '', // @stefvanesch
    ],

    'microsoft' => [
        'clarity' => [
            'tracking_id' => null,
        ],
    ],

    /*
     * For storing the SEO images
     */
    'storage' => [
        'disk' => 'public',
        'path' => 'seo',
    ],

    /*
    |--------------------------------------------------------------------------
    | SEO status
    |--------------------------------------------------------------------------
    |
    | Set SEO status, if its set to false then all pages will have
    | the 'noindex, nofollow' follow type and also removed the meta tags except the title tag.
    |
    */

    'seo_status' => env('SEO_STATUS', true),

    /*
    |--------------------------------------------------------------------------
    | SEO title formatter
    |--------------------------------------------------------------------------
    |
    | If you want a specific default format for your SEO titles, then you can
    | specify it here. Example could be ':text - Test site', then all pages would have
    | the ' - Test site' appended to the actual SEO title.
    |
    */

    'title_formatter' => ':text',

    /*
    |--------------------------------------------------------------------------
    | Follow type options
    |--------------------------------------------------------------------------
    |
    | Here is all the possible follow types shown in the admin panel
    | which is an array with key -> value.
    |
    */

    'follow_type_options' => [
        'index, follow' => 'Index and follow',
        'noindex, follow' => 'No index and follow',
        'index, nofollow' => 'Index and no follow',
        'noindex, nofollow' => 'No index and no follow',
    ],

    'page_types' => [
        'website' => 'Website',
        'article' => 'Article',
        'book' => 'Book',
        'profile' => 'Profile',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default follow type
    |--------------------------------------------------------------------------
    |
    | Set the default follow type.
    |
    */
    'default_follow_type' => env('SEO_DEFAULT_FOLLOW_TYPE', 'index, follow'),

    /*
    |--------------------------------------------------------------------------
    | Sitemap status
    |--------------------------------------------------------------------------
    |
    | Should there be a sitemap available
    |
    */
    'sitemap_status' => env('SITEMAP_STATUS', false),

    /*
    |--------------------------------------------------------------------------
    | Sitemap models
    |--------------------------------------------------------------------------
    |
    | Insert all the laravel models which should be in the sitemap
    |
    */
    'sitemap_models' => [],

    /*
    |--------------------------------------------------------------------------
    | Sitemap url
    |--------------------------------------------------------------------------
    |
    | Set the path of the sitemap
    |
    */
    'sitemap_path' => '/sitemap',
];
