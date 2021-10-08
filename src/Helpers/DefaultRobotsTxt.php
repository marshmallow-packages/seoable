<?php

namespace Marshmallow\Seoable\Helpers;

use Marshmallow\Seoable\Objects\Robots;

class DefaultRobotsTxt
{
    public function handle(Robots $robots): Robots
    {
        return $robots->userAgent('*')
            ->disallow('');
    }
}
