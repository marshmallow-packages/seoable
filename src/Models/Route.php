<?php

namespace Marshmallow\Seoable\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class Route extends Model
{
    public static function boot()
    {
        parent::boot();

        static::created(
            function (Route $route) {
                $route->reCacheRoutes();
            }
        );

        static::updated(
            function (Route $route) {
                $route->reCacheRoutes();
            }
        );

        static::deleted(
            function (Route $route) {
                $route->reCacheRoutes();
            }
        );
    }

    public function reCacheRoutes()
    {
        if (app()->routesAreCached()) {
            Artisan::queue('route:cache');
            // $exitcode = Artisan::call('route:cache');
            // dd($exitcode);
        }
    }

    public function scopeOrdered(Builder $builder)
    {
        $builder->orderBy('sequence', 'asc');
    }
}
