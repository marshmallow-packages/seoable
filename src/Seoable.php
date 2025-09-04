<?php

namespace Marshmallow\Seoable;

use Exception;
use Laravel\Nova\Panel;
use Marshmallow\Seoable\Seo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Outl1ne\MultiselectField\Multiselect;
use Laravel\Nova\Http\Requests\NovaRequest;
use Marshmallow\AdvancedImage\AdvancedImage;
use Marshmallow\Seoable\Http\Controllers\PrettyUrlController;

class Seoable
{
    protected static $fields = [
        'seoable_title',
        'seoable_description',
    ];

    public static function make(string $title = 'SEO')
    {
        return new Panel($title, self::getFields());
    }

    public static function makeAsTab(string $title = 'SEO')
    {
        $nova_tabs = '\Laravel\Nova\Tabs\Tab';
        if (class_exists($nova_tabs)) {
            return $nova_tabs::make(__('SEO'), self::getFields());
        }

        return self::make($title);
    }

    public static function asArray(): array
    {
        return self::getFields();
    }

    public static function getFields()
    {
        return array_filter([
            config('seo.fields.title', true) ? \Laravel\Nova\Fields\Text::make('Title', 'seoable_title')
                ->fillUsing(
                    function (NovaRequest $request, Model $model, $field) {
                        /*
                         * Only call the store method on the title.
                         * This method will store all the available fields.
                         */
                        app('seo')->set($model)->store($request, $field, 'title');
                    }
                )
                ->resolveUsing(
                    function ($name, Model $model, $field) {
                        $model = self::resolveModel($model);
                        return app('seo')->set($model)->getSeoTitle();
                    }
                )
                ->hideFromIndex()
                ->maxlength(60)
                ->hideWhenCreating() : null,

            config('seo.fields.description') ? \Laravel\Nova\Fields\Textarea::make('Description', 'seoable_description')
                ->fillUsing(
                    function (NovaRequest $request, Model $model, $field) {
                        /*
                         * Only call the store method on the title.
                         * This method will store all the available fields.
                         */
                        app('seo')->set($model)->store($request, $field, 'description');
                    }
                )
                ->resolveUsing(
                    function ($name, Model $model) {
                        $model = self::resolveModel($model);
                        return app('seo')->set($model)->getSeoDescription();
                    }
                )
                ->hideFromIndex()
                ->maxlength(160)
                ->hideWhenCreating() : null,

            config('seo.fields.keywords') ? Multiselect::make('Tags', 'seoable_tags')
                ->hideFromIndex()
                ->taggable()
                ->fillUsing(
                    function (NovaRequest $request, Model $model, $field) {
                        /*
                         * Only call the store method on the title.
                         * This method will store all the available fields.
                         */
                        app('seo')->set($model)->store($request, $field, 'keywords');
                    }
                )
                ->resolveUsing(
                    function ($name, Model $model) {
                        $model = self::resolveModel($model);
                        return app('seo')->set($model)->getSeoKeywords();
                    }
                )
                ->hideWhenCreating() : null,

            config('seo.fields.follow_type') ? Select::make('Follow type', 'seoable_follow_type')
                ->options(config('seo.follow_type_options'))
                ->fillUsing(
                    function (NovaRequest $request, Model $model, $field) {
                        /*
                         * Only call the store method on the title.
                         * This method will store all the available fields.
                         */
                        app('seo')->set($model)->store($request, $field, 'follow_type');
                    }
                )
                ->resolveUsing(
                    function ($name, Model $model) {
                        $model = self::resolveModel($model);
                        return app('seo')->set($model)->getSeoFollowType();
                    }
                )
                ->hideFromIndex()
                ->hideWhenCreating() : null,

            config('seo.fields.image') ? AdvancedImage::make(
                'Image',
                'seoable_image',
                config('seo.storage.disk')
            )
                ->croppable(1.91 / 1)
                ->resize(1200, 630)
                ->help('Your social share image should have a ratio of 1.91:1. We will resize the selected image to 1200x630')
                ->setCustomCallback(function ($request, $requestAttribute, $model, $attribute, $fileName) {
                    $request->{$requestAttribute} = $fileName;
                    app('seo')
                        ->set($model)
                        ->store($request, $requestAttribute, 'image');
                })
                ->store(
                    function (NovaRequest $request, Model $model, $attribute, $requestAttribute, $disk, $storagePath) {
                        $model = self::resolveModel($model);
                        $storage_location = Storage::disk(config('seo.storage.disk'))
                            ->putFile(
                                config('seo.storage.path'),
                                $request->file($requestAttribute)
                            );

                        $request->{$requestAttribute} = $storage_location;
                        app('seo')
                            ->set($model)
                            ->store($request, $requestAttribute, 'image');
                    }
                )
                ->customPreview(
                    function ($value, $disk, Model $model) {
                        return app('seo')
                            ->set($model)
                            ->getSeoImageUrl();
                    }
                )
                ->customThumbnail(
                    function ($value, $disk, Model $model) {
                        return app('seo')
                            ->set($model)
                            ->getSeoImageUrl();
                    }
                )
                ->hideFromIndex()
                ->hideWhenCreating() : null,

            config('seo.fields.page_type') ? Select::make('Page type', 'seoable_page_type')
                ->options(config('seo.page_types'))
                ->fillUsing(
                    function (NovaRequest $request, Model $model, $field) {
                        /*
                         * Only call the store method on the title.
                         * This method will store all the available fields.
                         */
                        app('seo')->set($model)->store($request, $field, 'page_type');
                    }
                )
                ->resolveUsing(
                    function ($name, Model $model) {
                        $model = self::resolveModel($model);
                        return app('seo')->set($model)->getSeoPageType();
                    }
                )
                ->hideFromIndex()
                ->hideWhenCreating() : null,

            config('seo.fields.sitemap') ? Boolean::make('Hide in sitemap', 'seoable_hide_in_sitemap')
                ->fillUsing(
                    function (NovaRequest $request, Model $model, $field) {
                        /*
                         * Only call the store method on the title.
                         * This method will store all the available fields.
                         */
                        app('seo')->set($model)->store($request, $field, 'hide_in_sitemap');
                    }
                )
                ->resolveUsing(
                    function ($name, Model $model) {
                        $model = self::resolveModel($model);
                        return app('seo')->set($model)->getHideInSitemap();
                    }
                )
                ->hideFromIndex()
                ->hideWhenCreating() : null,
        ]);
    }

    public static function routes()
    {
        if (self::shouldLoadRoutes()) {
            $routes = Seo::$routeModel::ordered()->get();

            if (config('seo.use_pretty_urls') === true) {
                Seo::$prettyUrlModel::get()->each(function ($prettyUrl) {
                    Route::get($prettyUrl->getRelativePath(), [PrettyUrlController::class, 'pretty']);
                });
            }

            foreach ($routes as $route) {
                $method = $route->method;
                $route_path = $route->path;

                if (method_exists($route, 'getTranslation')) {
                    $route_path = $route->getTranslation('path', Seo::$routeLocale);
                }

                try {
                    $_route = Route::{$method}($route_path, $route->controller);
                    if ($route->name) {
                        $_route = $_route->name($route->name);
                    }
                    if ($route->middleware) {
                        $middlewares = explode(',', $route->middleware);
                        collect($middlewares)->filter()->each(function ($middleware) use ($_route) {
                            $middleware = trim($middleware);
                            $_route = $_route->middleware($middleware);
                        });
                    }
                } catch (Exception $e) {
                    /*
                 * We only catch this Exception so no error's will be thrown
                 * if a controller of method doesnt exist. If we through this
                 * error, people won't be able to fix there mistake.
                 */
                }
            }
        }
    }

    protected static function shouldLoadRoutes(): bool
    {
        if (!Schema::hasTable('routes')) {
            /**
             * Don't load the routes if the pages table
             * doesnt exist. If this is the case, the
             * migrations haven't fully run yet.
             */
            return false;
        }

        return true;
    }

    public static function resolveModel(Model $model)
    {
        if (!$model->fresh() && request()->resourceId) {
            $model = get_class($model)::findOrFail(request()->resourceId);
        }
        return $model;
    }
}
