<?php

namespace Marshmallow\Seoable\ViewCreators;

use Illuminate\View\View;
use Marshmallow\Seoable\Facades\Seo;
use Spatie\GoogleTagManager\Exceptions\ApiKeyNotSetException;

class GoogleTagManagerScriptCreator
{
    protected $googleTagManagerEnabled;
    protected $googleTagManagerId;
    protected $googleTagManagerUrlSuffix;
    protected $addGtagFunction;

    public function __construct()
    {
        $this->googleTagManagerEnabled = Seo::googleTagManagerEnabled();
        $this->googleTagManagerId = Seo::googleTagManagerId();
        $this->googleTagManagerUrlSuffix =  Seo::googleTagManagerUrlSuffix();
        $this->addGtagFunction = Seo::addGtagFunction();
    }

    public function create(View $view)
    {
        $view
            ->with('enabled', $this->googleTagManagerEnabled)
            ->with('id', $this->googleTagManagerId)
            ->with('urlSuffix', $this->googleTagManagerUrlSuffix)
            ->with('addGtagFunction', $this->addGtagFunction);
    }
}
