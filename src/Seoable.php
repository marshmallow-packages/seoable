<?php

namespace Marshmallow\Seoable;

use Exception;
use Laravel\Nova\Panel;
use Marshmallow\Seoable\Seo;
use Laravel\Nova\Fields\Select;
use Outl1ne\MultiselectField\Multiselect as MMMultiselect;
use Laravel\Nova\Fields\Boolean;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Http\Requests\NovaRequest;
use Marshmallow\AdvancedImage\AdvancedImage;
use Marshmallow\CharcountedFields\TextCounted;
use Marshmallow\CharcountedFields\TextareaCounted;
use Marshmallow\Seoable\Http\Controllers\PrettyUrlController;

class Seoable
{
    protected static $fields = [
        'seoable_title',
        'seoable_description',
    ];

    public static function fillField(NovaRequest $request, Model $model, $field)
    {
        $value = $request->input($field);
        if ($field == 'seoable_hide_in_sitemap') {
            $value = $value == 0 ? false : true;
        }

        $request->request->remove($field);
        $field = str_after($field, 'seoable_');
        return $model->seoable->update([
            $field => $value,
        ]);
    }

    public static function resolveField($name, Model $model, $field)
    {
        $field = str_after($field, 'seoable_');

        $value = $model->seoable?->$field;

        if ($field == 'hide_in_sitemap') {
            $value = $value == 0 ? false : true;
        }

        return $value;
    }

    public static function saveFile(NovaRequest $request, Model $model, $field)
    {
        $disk = config('seo.storage.disk');
        $path = config('seo.storage.path');
        $file = $request->file($field);
        $storage_location = Storage::disk($disk)->putFile($path, $file);
        $request->request->remove($field);

        $field = str_after($field, 'seoable_');
        return $model->seoable->update([
            $field => $storage_location,
        ]);
    }

    public static function make($title)
    {
        return new Panel($title, [
            TextCounted::make('Title', 'seoable_title')
                ->hideFromIndex()
                ->minChars(30)
                ->maxChars(60)
                ->warningAt(50)
                ->hideWhenCreating()
                ->fillUsing(function (NovaRequest $request, Model $model, $field) {
                    return self::fillField($request, $model, $field);
                })->resolveUsing(function ($name, Model $model, $field) {
                    return self::resolveField($name, $model, $field);
                }),

            TextareaCounted::make('Description', 'seoable_description')
                ->hideFromIndex()
                ->minChars(70)
                ->maxChars(160)
                ->warningAt(150)
                ->hideWhenCreating()
                ->fillUsing(function (NovaRequest $request, Model $model, $field) {
                    return self::fillField($request, $model, $field);
                })->resolveUsing(function ($name, Model $model, $field) {
                    return self::resolveField($name, $model, $field);
                }),

            MMMultiselect::make('Keywords', 'seoable_keywords')
                ->hideFromIndex()
                ->taggable()
                ->hideWhenCreating()
                ->fillUsing(function (NovaRequest $request, Model $model, $field) {
                    return self::fillField($request, $model, $field);
                })->resolveUsing(function ($name, Model $model, $field) {
                    return self::resolveField($name, $model, $field);
                }),

            Select::make('Follow type', 'seoable_follow_type')
                ->options(config('seo.follow_type_options'))
                ->hideFromIndex()
                ->hideWhenCreating()
                ->fillUsing(function (NovaRequest $request, Model $model, $field) {
                    return self::fillField($request, $model, $field);
                })->resolveUsing(function ($name, Model $model, $field) {
                    return self::resolveField($name, $model, $field);
                }),

            AdvancedImage::make(
                'Image',
                'seoable_image',
                config('seo.storage.disk')
            )
                ->croppable(1.91 / 1)
                ->resize(1200, 630)
                ->help('Your social share image should have a ratio of 1.91:1. We will resize the selected image to 1200x630')
                ->hideFromIndex()
                ->hideWhenCreating()
                ->resolveUsing(function ($name, Model $model, $field) {
                    return self::resolveField($name, $model, $field);
                })
                ->store(function ($request, $model, $attribute) {
                    return self::saveFile($request, $model, $attribute);
                }),

            Select::make('Page type', 'seoable_page_type')
                ->options(config('seo.page_types'))
                ->hideFromIndex()
                ->hideWhenCreating()
                ->fillUsing(function (NovaRequest $request, Model $model, $field) {
                    return self::fillField($request, $model, $field);
                })->resolveUsing(function ($name, Model $model, $field) {
                    return self::resolveField($name, $model, $field);
                }),

            Boolean::make('Hide in sitemap', 'seoable_hide_in_sitemap')
                ->hideFromIndex()
                ->hideWhenCreating()
                ->fillUsing(function (NovaRequest $request, Model $model, $field) {
                    return self::fillField($request, $model, $field);
                })->resolveUsing(function ($name, Model $model, $field) {
                    return self::resolveField($name, $model, $field);
                }),
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
