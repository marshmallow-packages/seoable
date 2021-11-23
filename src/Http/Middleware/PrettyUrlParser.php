<?php

namespace Marshmallow\Seoable\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Marshmallow\Seoable\Seo as BaseSeo;
use Marshmallow\Seoable\Models\PrettyUrl;
use Marshmallow\Seoable\Http\Controllers\PrettyUrlController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PrettyUrlParser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('prettier_route_response')) {
            return response()->json($request->route());
        }

        if ($this->routeIsPretty($request)) {
            $pretty_url = BaseSeo::$prettyUrlModel::byPath($request->path())->first();
            $pretty_url->checkAndSetCanonical();
            $pretty_url->setSeoableContent(true);
            $request = Request::createRequestFromPrettyUrl($request);
        } elseif ($pretty_url = $this->routeIsPrettyfied($request)) {
            if ($pretty_url->shouldBeRedirected()) {
                return $pretty_url->getRedirectToPretty();
            } elseif ($pretty_url->shouldUseAsCanonical()) {
                $pretty_url->checkAndSetCanonical();
            }
        }

        return $next($request);
    }

    /**
     * Check if this ugly URL has a pretty version
     *
     * @param Request $request
     * @return PrettyUrl|null
     */
    protected function routeIsPrettyfied(Request $request): ?PrettyUrl
    {
        $pretty_url = BaseSeo::$prettyUrlModel::byOriginalPath($request->getRequestUri())->first();
        return $pretty_url ?? null;
    }

    /**
     * Check if this URL is a pretty version of an ugly one
     *
     * @param Request $request
     * @return bool
     */
    protected function routeIsPretty(Request $request): bool
    {
        try {
            $routes = Route::getRoutes();
            $match = $routes->match($request);
            return (isset($match->action['controller']) && $match->action['controller'] == PrettyUrlController::class . '@pretty');
        } catch (NotFoundHttpException $e) {
            return false;
        }
    }
}
