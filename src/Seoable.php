<?php

namespace Marshmallow\Seoable;

use Laravel\Nova\Panel;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Marshmallow\Seoable\Fields\Tags;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Http\Requests\NovaRequest;
use Marshmallow\Seoable\Models\Route as RouteModel;

class Seoable
{
	protected static $fields = [
		'seoable_title',
		'seoable_description',
	];

	public static function make ($title)
	{
		return new Panel($title, [

            Text::make('Title', 'seoable_title')->fillUsing(function (NovaRequest $request, Model $model, $field) {
            	/**
            	 * Only call the store method on the title.
            	 * This method will store all the available fields.
            	 */
                app('seo')->set($model)->store($request, $field, 'title');

            })->resolveUsing(function ($name, Model $model) {
            	return app('seo')->set($model)->getSeoTitle();
            })->hideFromIndex()->hideWhenCreating(),


            Textarea::make('Description', 'seoable_description')->fillUsing(function (NovaRequest $request, Model $model, $field) {

                /**
            	 * Only call the store method on the title.
            	 * This method will store all the available fields.
            	 */
                app('seo')->set($model)->store($request, $field, 'description');

            })->resolveUsing(function ($name, Model $model) {
            	return app('seo')->set($model)->getSeoDescription();
            })->hideFromIndex()->hideWhenCreating(),

            Tags::make('Tags', 'seoable_tags')->withoutSuggestions()->hideFromIndex()->hideWhenCreating(),

            Select::make('Follow type', 'seoable_follow_type')->options(config('seo.follow_type_options'))->fillUsing(function (NovaRequest $request, Model $model, $field) {
                /**
                 * Only call the store method on the title.
                 * This method will store all the available fields.
                 */
                app('seo')->set($model)->store($request, $field, 'follow_type');

            })->resolveUsing(function ($name, Model $model) {
                return app('seo')->set($model)->getSeoFollowType();
            })->hideFromIndex()->hideWhenCreating(),

            Image::make('Image', 'seoable_image')->store(function (NovaRequest $request, Model $model, $attribute, $requestAttribute, $disk, $storagePath) {

                $storage_location = Storage::disk(config('seo.storage.disk'))->putFile(config('seo.storage.path'), $request->file($requestAttribute));
                $request->{$requestAttribute} = $storage_location;
                app('seo')->set($model)->store($request, $requestAttribute, 'image');

            })->preview(function ($value, $disk, Model $model) {
                return app('seo')->set($model)->getSeoImageUrl();
            })->hideFromIndex()->hideWhenCreating(),
        ]);
	}

    public static function routes ()
    {
        $routes = RouteModel::ordered()->get();
        foreach ($routes as $route) {
            $method = $route->method;

            $_route = Route::{$method}($route->path, $route->controller);
            if ($route->name) {
                $_route = $_route->name($route->name);
            }
            if ($route->middleware) {
                $_route = $_route->middleware($route->middleware);
            }
        }
    }
}
