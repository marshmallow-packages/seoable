<?php

namespace Marshmallow\Seoable\Nova;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Marshmallow\Seoable\Rules\IsFullLocalUrl;

class PrettyUrl extends Resource
{
    public static $group = 'SEO';

    public static $group_icon = '<svg class="sidebar-icon" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="icon-shape"><path fill="var(--sidebar-icon)" d="M15,9 C16.6568542,9 18,7.65685425 18,6 L20,6 C20,8.76142375 17.7614237,11 15,11 C14.9666494,11 14.9333751,10.9996735 14.90018,10.9990235 C14.5028122,12.9586176 12.9594559,14.5022333 11,14.8999819 L11,17 L16,19 L16,20 L4,20 L4,19 L9,17 L9,14.8999819 C7.04054412,14.5022333 5.49718782,12.9586176 5.09981999,10.9990235 C5.06662494,10.9996735 5.0333506,11 5,11 C2.23857625,11 0,8.76142375 0,6 L2,6 C2,7.65685425 3.34314575,9 5,9 L5,4 L2,4 L2,6 L0,6 L0,2 L5,2 L5,0 L15,0 L15,2 L20,2 L20,6 L18,6 L18,4 L15,4 L15,9 Z" id="Combined-Shape"></path></g></g></svg>';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Marshmallow\Seoable\Models\PrettyUrl';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'pretty_url';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'original_url', 'pretty_url',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Original Url', 'original_url')
                ->sortable()
                ->required()
                ->help('Please provide the full URL which you want to prettify. This should include https://')
                ->displayUsing(function ($value) {
                    return "<a href='{$value}' target='_blank'>{$value}</a>";
                })->asHtml(),

            Text::make('Pretty Url', 'pretty_url')
                ->sortable()
                ->required()
                ->help('Please provide the full pretty URL. This should include https://')
                ->creationRules([
                    'unique:pretty_urls,pretty_url',
                    'different:original_url',
                    new IsFullLocalUrl
                ])
                ->updateRules([
                    'unique:pretty_urls,pretty_url,{{resourceId}}',
                    'different:original_url',
                    new IsFullLocalUrl
                ])
                ->displayUsing(function ($value) {
                    return "<a href='{$value}' target='_blank'>{$value}</a>";
                })->asHtml(),

            Boolean::make('Is canonical', 'is_canonical')
                ->sortable()
                ->help('Should this pretty URL be used as the canonical url? Check this if the anwser is Yes')
                ->default(true),

            Boolean::make('Should redirect', 'should_redirect')
                ->sortable()
                ->help('Should a visitor that lands on the original url be redirected to the pretty version? By default, this is not the case.'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
