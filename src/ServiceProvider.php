<?php

namespace Marshmallow\Seoable;

use Laravel\Nova\Nova;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Contracts\Http\Kernel;
use Marshmallow\Seoable\Models\PrettyUrl;
use Marshmallow\Seoable\Helpers\PrettyUrlResolver;
use Marshmallow\Seoable\Http\Middleware\PrettyUrlParser;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Seo::class, function () {
            return new Seo();
        });

        $this->app->alias(Seo::class, 'seo');

        Nova::serving(function (ServingNova $event) {
            Nova::script('seoable', __DIR__ . '/../dist/js/field.js');
            Nova::style('seoable', __DIR__ . '/../dist/css/field.css');
        });

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'seoable');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->publishes([
            __DIR__ . '/../config/seo.php' => config_path('seo.php'),
        ]);

        $this->registerPrettyUrlMacro();

        if (config('seo.use_pretty_urls') === true) {
            $this->registerMiddleware();
        }

        $this->app['view']->creator(
            ['seoable::google.gtm-head', 'seoable::google.gtm-body'],
            'Marshmallow\Seoable\ViewCreators\GoogleTagManagerScriptCreator'
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/seo.php', 'seo');
    }

    /**
     * This Macro will check if the currently
     *
     * @return void
     */
    protected function registerPrettyUrlMacro()
    {
        Request::macro('createRequestFromPrettyUrl', function (Request $from, $to = null) {

            $to = ($to) ? $to : new static;
            $resolver = new Seo::$prettyUrlResolver($from, $to);
            return $resolver->resolve()
                ->append()
                ->run();
        });
    }

    protected function registerMiddleware()
    {
        $this->app->make(Kernel::class)->prependMiddleware(PrettyUrlParser::class);
    }
}
