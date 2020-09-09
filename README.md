# SEO meta field nova
This custom nova field, can add SEO related fields to any Model through a morph relationship within one single trait.

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

## Use sluggable
This package also includes `marshmallow/sluggable` by default. We do this because to make sure all seo driven website will use the same logic for building slugs. The package it self does not use `marshmallow/sluggable` so you can choose any other sluggable package if you wish to do so.

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
    use SeoableTrait, SeoSitemapTrait;
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


## How does it look in Laravel Nova
If the field is shown **in the index view** of the Resource, then you should see a column with a dot:
![alt text](/assets/images/seo-field-index.jpg)

**In detail view** you will see a text saying `You need some SEO data` if no SEO is setup yet. But if you have any then, you will get the toggle button, which will show you an example how it will look like on Google and on Facebook:
![alt text](/assets/images/seo-field-detail-hidden.jpg)
![alt text](/assets/images/seo-field-detail-show.jpg)


**In form view** you should see all the SEO input fields:
![alt text](/assets/images/seo-field-form.jpg)
