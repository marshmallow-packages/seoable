<?php

namespace Marshmallow\Seoable\Contracts;

use Marshmallow\Seoable\Objects\Robots;

interface RobotTxtInterface
{
    public function handle(Robots $robots): Robots;
}
