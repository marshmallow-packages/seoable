<?php

namespace Marshmallow\Seoable;

use Laravel\Nova\Nova;
use Illuminate\Http\Request;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Contracts\Http\Kernel;
use Marshmallow\Seoable\Models\PrettyUrl;
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

        if (config('seo.use_pretty_urls') === true) {
            $this->registerPrettyUrlMacro();
            $this->registerMiddleware();
        }
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

            $pretty_url = PrettyUrl::byPath($from->path())->first();
            if (!$pretty_url) {
                return $from;
            }

            $from->server->set('REQUEST_URI', $pretty_url->getCleanPath('original_url'));

            $request = $to ?: new static;

            $files = $from->files->all();

            $files = is_array($files) ? array_filter($files) : $files;



            // if ('/henk-jan-smit' == $from->server->all()['REQUEST_URI']) {
            //
            // }

            $request->initialize(
                $from->query->all(),
                $from->request->all(),
                $from->attributes->all(),
                $from->cookies->all(),
                $files,
                $from->server->all(),
                $from->getContent()
            );

            $request->headers->replace($from->headers->all());

            $request->setJson($from->json());

            if ($session = $from->getSession()) {
                $request->setLaravelSession($session);
            }

            $request->setUserResolver($from->getUserResolver());

            $request->setRouteResolver($from->getRouteResolver());

            return $request;
        });
    }

    protected function registerMiddleware()
    {
        $this->app->make(Kernel::class)->prependMiddleware(PrettyUrlParser::class);
    }
}
