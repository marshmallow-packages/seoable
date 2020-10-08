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

    public static $group_icon = '<svg class="sidebar-icon" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="icon-shape"><path fill="var(--sidebar-icon)" d="M15,9 C16.6568542,9 18,7.65685425 18,6 L20,6 C20,8.76142375 17.7614237,11 15,11 C14.9666494,11 14.9333751,10.9996735 14.90018,10.9990235 C14.5028122,12.9586176 12.9594559,14.5022333 11,14.8999819 L11,17 L16,19 L16,20 L4,20 L4,19 L9,17 L9,14.8999819 C7.04054412,14.5022333 5.49718782,12.9586176 5.09981999,10.9990235 C5.06662494,10.9996735 5.0333506,11 5,11 C2.23857625,11 0,8.76142375 0,6 L2,6 C2,7.65685425 3.34314575,9 5,9 L5,4 L2,4 L2,6 L0,6 L0,2 L5,2 L5,0 L15,0 L15,2 L20,2 L20,6 L18,6 L18,4 L15,4 L15,9 Z" id="Combined-Shape"></path></g></g></svg>';

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
