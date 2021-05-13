<?php

namespace Marshmallow\Seoable\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Marshmallow\Seoable\Models\PrettyUrl;

class PrettyUrlController extends Controller
{
    public function pretty(Request $request)
    {
        abort(404);
    }
}
