# Seoable — SEO meta fields for Laravel Nova

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marshmallow/seoable.svg?style=flat-square)](https://packagist.org/packages/marshmallow/seoable)
[![Total Downloads](https://img.shields.io/packagist/dt/marshmallow/seoable.svg?style=flat-square)](https://packagist.org/packages/marshmallow/seoable)
[![License](https://img.shields.io/packagist/l/marshmallow/seoable.svg?style=flat-square)](https://packagist.org/packages/marshmallow/seoable)

A Laravel Nova field that adds all SEO related meta fields to any Resource. SEO data is attached to any Eloquent model through a morph relationship using a single trait, and rendered into your layout with one facade call. The package also ships sitemap generation, a customisable `robots.txt`, structured data (JSON-LD) schemas, pretty URLs, and built-in support for Google Tag Manager, Google Analytics, Cookiebot, Hotjar, Microsoft Clarity and Facebook tags.

## Requirements

- PHP `^8.1`
- [Laravel Nova](https://nova.laravel.com/) `^5.0`

## How to install

To install the package run the install below:

```
composer require marshmallow/seoable
```

And then run the migrations:

```
php artisan migrate
```

And then publish the configs:

```
php artisan vendor:publish --provider="Marshmallow\Seoable\ServiceProvider"
```

## PLEASE NOTE

If you are using route caching you need to make sure you have a queue:work running. If you change a route we will recache your routes automaticly but this is done via a queue.

## Manually

You can change the SEO data with the methods below.

```php
use Marshmallow\Seoable\Facades\Seo;

Seo::setTitle(string $title);
Seo::setDescription(string $description);
Seo::setKeywords(array $keywords);
Seo::setImage(string $image);
Seo::setFollowType(string $follow_type);
Seo::setHrefs(array $hrefs);
Seo::setHideInSitemap(bool $hide_in_sitemap);
Seo::setLocale(string $locale);
Seo::setHtmlLanguage(string $html_language);
Seo::setSeoCanonicalUrl(string $canonical);
```

## How to use the field

Find the model you want to have the SEO fields on, example could be `App\Models\Page`, then add the `Seoable` trait:

```
...
use Marshmallow\Seoable\Traits\Seoable;

class Page extends Model
{
    use Seoable;
    ...
}
```

Then use the field in the nova resource `App\Nova\Page`:

```
...
use Marshmallow\Seoable\Seoable;

class Page extends Resource
{
  ...
  public function fields(Request $request)
  {
    return [
      ...,
      Seoable::make('Seo'),
    ];
  }
}
```

You can also render the field inside a Nova tab with `Seoable::makeAsTab('Seo')`.

Then go to the top of your layout blade as default it's `resources/views/welcome.blade.php`:

```
...
<head>
    {{ Seo::generate() }}
    ...
</head>
```

Last step! Tell the SEO Facade which model it can use to set the SEO data.

```
use Marshmallow\Seoable\Facades\Seo;

class ExampleController extends Controller
{
    public function show (Product $product)
    {
        $product->useForSeo();

        return view('product')->with([
            'product' => $product
        ])
    }
}

```

## Publish the Nova Resources

```bash
php artisan marshmallow:resource Route Seoable
php artisan marshmallow:resource PrettyUrl Seoable
```

## Use sluggable (optional)

SEO-driven websites usually need clean, slug-based URLs. This package does **not** depend on any slug package itself, so you are free to use whichever one you like. At Marshmallow we use [`marshmallow/sluggable`](https://github.com/marshmallow-packages/sluggable) so every SEO site shares the same slug-building logic — install it separately if you want the same setup:

```bash
composer require marshmallow/sluggable
```

```php
class YourEloquentModel extends Model
{
    use HasSlug;

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
```

## Use routes

```bash
php artisan marshmallow:resource Route Seoable
```

Add the following to your `routes/web.php`.

```php
use Marshmallow\Seoable\Seoable;

Seoable::routes();
```

## Use pretty URL's

If you wish to use the pretty urls module, you need to activate this in your config. This is set to `false` by default. This module will register a middleware for you. Because this functionality is currently in beta it is disabled by default. If you find any issues, please let us know.

```php
// config/seo.php

return [
    'use_pretty_urls' => true,
];
```

## Setup default values for a model

You can overrule how the seo defaults per model are handled. You can use the methods below.

```
// Return the SEO title for the model
public function getSeoTitle(): ?string

// Return the SEO description for the model
public function setSeoDescription(): ?string

// Return the SEO keywords for the model
public function setSeoKeywords(): ?array

// Return the SEO image for the model
public function setSeoImage(): ?string

// Return the SEO follow type for the model
public function setSeoFollowType(): ?string
```

## Setup Sitemap functionality

If you want the sitemap functionality then activate the sitemap by changing the `seo.sitemap_status` config to `true`. Then add the models which has the `SeoSitemapTrait` trait to the `seo.sitemap_models` array, like this:

```
    ...
    'sitemap_status' => env('SITEMAP_STATUS', true),

    ...
    'sitemap_models' => [
        App\Models\Page::class
    ],
```

### Add Sitemap trait to models

When you want the eloquent model to be shown in the sitemap then you need to add the `SeoSitemapTrait` trait to it:

```
...
use Marshmallow\Seoable\Traits\SeoSitemapTrait;

class Page extends Model
{
    use Seoable, SeoSitemapTrait;
    ...

    /**
     * Get the Page url by item
     *
     * @return string
     */
    public function getSitemapItemUrl()
    {
        return url($this->slug);
    }

    /**
     * Query all the Page items which should be
     * part of the sitemap (crawlable for google).
     *
     * @return Builder
     */
    public static function getSitemapItems()
    {
        return static::all();
    }
}
```

Know you should be able to go to the `seo.sitemap_path` which is `/sitemap` as default. Then you should get an xml in the correct sitemap structure for [Google Search Console](https://search.google.com/search-console/about).

## Structured Data

```php
$faq = \Marshmallow\Seoable\Helpers\Schemas\SchemaFaqPage::make();
$faq->addQuestionAndAnswer('What is the name of this company?', 'Marshmallow');

\Marshmallow\Seoable\Facades\Seo::addSchema($faq);
```

## Robots

This package also allows you to use a customer Robots.txt. You should create a helper class where you will add your Robots.txt content. For instance, create a class in `app/Helpers/RobotTxt.php`. In this class you should implement a `handle` method. This package will call the method for you and output the result.

```php

# app/Helpers/RobotTxt.php

namespace App\Helpers;

use Marshmallow\Seoable\Objects\Robots;
use Marshmallow\Seoable\Contracts\RobotTxtInterface;

class RobotTxt implements RobotTxtInterface
{
    public function handle(Robots $robots): Robots
    {
        return $robots->userAgent('*')
            ->allow('/')
            ->disallow('/login');
    }
}

```

### Implementation robots

Once you've created the helper class you should let the seoable config know where to find this helper.

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Robots.txt
    |--------------------------------------------------------------------------
    |
    | Override the class which builds the robots.txt file. If you are using this,
    | do not forget to delete the original public/robots.txt file.
    |
    */
    'robots_resolver' => App\Helpers\DefaultRobotsTxt::class,
];
```

To complete the implementation, you only need the remove the default `public/robots.txt` file and you are good to go!

## How does it look in Laravel Nova

If the field is shown **in the index view** of the Resource, then you should see a column with a dot:
![alt text](/assets/images/seo-field-index.jpg)

**In detail view** you will see a text saying `You need some SEO data` if no SEO is setup yet. But if you have any then, you will get the toggle button, which will show you an example how it will look like on Google and on Facebook:
![alt text](/assets/images/seo-field-detail-hidden.jpg)
![alt text](/assets/images/seo-field-detail-show.jpg)

**In form view** you should see all the SEO input fields:
![alt text](/assets/images/seo-field-form.jpg)

## Credits

- [Stef van Esch](https://github.com/marshmallow-packages)
- [All Contributors](https://github.com/marshmallow-packages/seoable/contributors)

## Security

If you discover any security related issues, please email stef@marshmallow.dev instead of using the issue tracker.

## License

The MIT License (MIT). Please see the [License File](https://packagist.org/packages/marshmallow/seoable) for more information.
