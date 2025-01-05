<?php

namespace Marshmallow\Seoable\Nova;

use App\Nova\Resource;
use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Heading;
use Marshmallow\Seoable\Seoable;
use Marshmallow\Nova\Fields\Help\Help;
use Marshmallow\Nova\Flexible\Flexible;
use Marshmallow\Seoable\Rules\IsFullLocalUrl;

class PrettyUrl extends Resource
{
    public static $group = 'SEO';

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

    public function title()
    {
        if ($this->name) {
            return $this->name;
        }

        return $this->{$this::$title};
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'original_url',
        'pretty_url',
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $table = $this->getTableWithConnectionName();

        return [
            Text::make('Name', 'name')
                ->sortable()
                ->required()
                ->help('This name is for internal use only. This will make it easer to find this pretty url in the future'),

            Text::make('Original Url', 'original_url')
                ->sortable()
                ->required()
                ->help('Please provide the full URL which you want to prettify. This should include https://')
                ->displayUsing(function ($value) {
                    return "<a href='{$value}' target='_blank'>{$this->getDisplayableLink($value)}</a>";
                })->asHtml(),

            Text::make('Pretty Url', 'pretty_url')
                ->sortable()
                ->required()
                ->help('Please provide the full pretty URL. This should include https://')
                ->creationRules([
                    "unique:{$table},pretty_url",
                    'different:original_url',
                    new IsFullLocalUrl
                ])
                ->updateRules([
                    "unique:{$table},pretty_url,{{resourceId}}",
                    'different:original_url',
                    new IsFullLocalUrl
                ])
                ->displayUsing(function ($value) {
                    return "<a href='{$value}' target='_blank'>{$this->getDisplayableLink($value)}</a>";
                })->asHtml(),

            Boolean::make('Is canonical', 'is_canonical')
                ->sortable()
                ->help('Should this pretty URL be used as the canonical url? Check this if the anwser is Yes')
                ->default(true),

            Boolean::make('Should redirect', 'should_redirect')
                ->sortable()
                ->help('Should a visitor that lands on the original url be redirected to the pretty version? By default, this is not the case.'),

            Heading::make(__('Seoable content')),
            Help::make(__('How does this work?'), __('You can add content here that you need to be present on this pretty version of the page. All items need to be placed on the page by a developer. These items are not fixed to a specific place. This is done to be extremly flexible on where we place this content and we can make sure the design will remain awesome')),
            Flexible::make(__('Content'), 'seoable_content')
                ->addLayout(__('Seoable content'), 'seoable_content', [
                    Select::make(__('Type'), 'type')->options([
                        'header' => 'Header',
                        'footer' => 'Footer',
                    ]),
                    Text::make(__('Title'), 'title'),
                    Trix::make(__('Content'), 'content'),
                ])
                ->simpleMenu()
                ->fullWidth()
                ->collapsed()
                ->button(__('Add more content')),

            Seoable::make(__('SEO')),
        ];
    }

    protected function getTableWithConnectionName()
    {
        return 'pretty_urls';
    }

    protected function getDisplayableLink($value, $limit = 100)
    {
        return Str::of($value)->replace(config('app.url'), '')->limit($limit, '...');
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
