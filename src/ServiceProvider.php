<?php

namespace Marshmallow\Seoable;

use Laravel\Nova\Nova;
use Marshmallow\Seoable\Seo;
use Laravel\Nova\Events\ServingNova;
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
            Nova::script('seoable', __DIR__.'/../dist/js/field.js');
            Nova::style('seoable', __DIR__.'/../dist/css/field.css');
        });

        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'seoable');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->publishes([
            __DIR__ . '/config/seo.php' => config_path('seo.php'),
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/seo.php', 'seo');
    }
}
