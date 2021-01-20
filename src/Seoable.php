<?php

namespace Marshmallow\Seoable;

use Exception;
use Laravel\Nova\Panel;
use Laravel\Nova\Fields\Select;
use Marshmallow\TagsField\Tags;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Http\Requests\NovaRequest;
use Marshmallow\AdvancedImage\AdvancedImage;
use Marshmallow\CharcountedFields\TextCounted;
use Marshmallow\CharcountedFields\TextareaCounted;
use Marshmallow\Seoable\Models\Route as RouteModel;

class Seoable
{
    protected static $fields = [
        'seoable_title',
        'seoable_description',
    ];

    public static function make($title)
    {
        return new Panel($title, [
            TextCounted::make('Title', 'seoable_title')
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
                    function ($name, Model $model) {
                        return app('seo')->set($model)->getSeoTitle();
                    }
                )
                ->hideFromIndex()
                ->minChars(30)
                ->maxChars(60)
                ->warningAt(50)
                ->hideWhenCreating(),

            TextareaCounted::make('Description', 'seoable_description')
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
                        return app('seo')->set($model)->getSeoDescription();
                    }
                )
                ->hideFromIndex()
                ->minChars(70)
                ->maxChars(160)
                ->warningAt(150)
                ->hideWhenCreating(),

            Tags::make('Tags', 'seoable_tags')
                ->hideFromIndex()
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
                        return app('seo')->set($model)->getSeoKeywords();
                    }
                )
                ->hideWhenCreating(),

            Select::make('Follow type', 'seoable_follow_type')
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
                        return app('seo')->set($model)->getSeoFollowType();
                    }
                )
                ->hideFromIndex()
                ->hideWhenCreating(),

            AdvancedImage::make(
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
                ->hideWhenCreating(),

             Select::make('Page type', 'seoable_page_type')
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
                        return app('seo')->set($model)->getSeoPageType();
                    }
                )
                ->hideFromIndex()
                ->hideWhenCreating(),
        ]);
    }

    public static function routes()
    {
        $routes = RouteModel::ordered()->get();
        foreach ($routes as $route) {
            $method = $route->method;

            try {
                $_route = Route::{$method}($route->path, $route->controller);
                if ($route->name) {
                    $_route = $_route->name($route->name);
                }
                if ($route->middleware) {
                    $_route = $_route->middleware($route->middleware);
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
