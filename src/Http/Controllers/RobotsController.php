<?php

namespace Marshmallow\Seoable\Http\Controllers;

use App\Http\Controllers\Controller;
use Marshmallow\Seoable\Objects\Robots;

class RobotsController extends Controller
{
    public function render()
    {
        $robots_resolver = config('seo.robots_resolver');
        $robots = new Robots;

        return (new $robots_resolver)
            ->handle($robots)
            ->output();
    }
}
