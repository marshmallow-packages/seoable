<?php

namespace Marshmallow\Seoable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Route extends Model
{
    public function scopeOrdered(Builder $builder)
    {
        $builder->orderBy('sequence', 'asc');
    }
}
