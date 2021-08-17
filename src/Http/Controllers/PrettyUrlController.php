<?php

namespace Marshmallow\Seoable\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PrettyUrlController extends Controller
{
    public function pretty(Request $request)
    {
        abort(404);
    }
}
