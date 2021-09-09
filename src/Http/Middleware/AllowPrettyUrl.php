<?php

namespace Marshmallow\Seoable\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowPrettyUrl
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
        if ($request->has('prettyfy')) {
            return response()->json($request->route());
        }
        return $next($request);
    }
}
