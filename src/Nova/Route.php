<?php

namespace Marshmallow\Seoable\Nova;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Marshmallow\HelperFunctions\Facades\User;

class Route extends Resource
{
    public static $group = 'SEO';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Marshmallow\Seoable\Models\Route';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Path')
                ->sortable()
                ->required()
                ->help('Please note that if you change this value all indexed urls will be forwarded to the new version. Also, it can take up to 5 minutes for your changes to take affect because of caching so please be patient if your change isnt visable directly.'),

            Select::make('Method')
                ->sortable()
                ->required()
                ->options(
                    [
                        'get' => 'GET',
                        'post' => 'POST',
                        'put' => 'PUT',
                        'delete' => 'DELETE',
                    ]
                )->displayUsingLabels()
                ->readonly(
                    function () {
                        return (!User::isMarshmallow(request()->user()));
                    }
                )
                ->help('This can only be changed by Marshmallow employees.'),

            Text::make('Controller')
                ->sortable()
                ->required()
                ->readonly(
                    function () {
                        return (!User::isMarshmallow(request()->user()));
                    }
                )
                ->help('This can only be changed by Marshmallow employees.'),

            Text::make('Name')
                ->sortable()
                ->readonly(
                    function () {
                        return (!User::isMarshmallow(request()->user()));
                    }
                )
                ->help('This can only be changed by Marshmallow employees.'),

            Text::make('Middleware')
                ->sortable()
                ->readonly(
                    function () {
                        return (!User::isMarshmallow(request()->user()));
                    }
                )
                ->help('This can only be changed by Marshmallow employees.'),

            Number::make('Sequence')
                ->sortable()
                ->readonly(
                    function () {
                        return (!User::isMarshmallow(request()->user()));
                    }
                )
                ->withMeta($this->sequence ? [] : [
	                'value' => 999,
	            ])
                ->help('This can only be changed by Marshmallow employees.'),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
