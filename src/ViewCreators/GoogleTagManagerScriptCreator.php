<?php

namespace Marshmallow\Seoable\ViewCreators;

use Illuminate\View\View;
use Marshmallow\Seoable\Facades\Seo;
use Spatie\GoogleTagManager\Exceptions\ApiKeyNotSetException;

class GoogleTagManagerScriptCreator
{
    protected $googleTagManagerId;
    protected $googleTagManagerUrlSuffix;

    public function __construct()
    {
        $this->googleTagManagerId = config('seo.google.GTM');
        $this->googleTagManagerUrlSuffix =  Seo::googleTagManagerUrlSuffix();
    }

    public function create(View $view)
    {
        $view
            ->with('enabled', $this->googleTagManagerId ? true : false)
            ->with('id', $this->googleTagManagerId)
            ->with('urlSuffix', $this->googleTagManagerUrlSuffix);
    }
}
