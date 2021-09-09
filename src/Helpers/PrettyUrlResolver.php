<?php

namespace Marshmallow\Seoable\Helpers;

use Illuminate\Http\Request;
use Marshmallow\Seoable\Seo;

class PrettyUrlResolver
{
    protected $to;
    protected $from;
    protected $request;
    protected $pretty_url;

    public function __construct(Request $from, $to = null)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function resolve(): PrettyUrlResolver
    {
        $pretty_url = Seo::$prettyUrlModel::byPath($this->from->path())->first();
        if (!$pretty_url) {
            $this->request = $this->from;
        } else {
            $this->pretty_url = $pretty_url;
            $this->from->server->set('REQUEST_URI', $pretty_url->getCleanPath('original_url'));

            $request = $this->to ?: new Request;

            $files = $this->from->files->all();

            $files = is_array($files) ? array_filter($files) : $files;

            $request->initialize(
                $this->from->query->all(),
                $this->from->request->all(),
                $this->from->attributes->all(),
                $this->from->cookies->all(),
                $files,
                $this->from->server->all(),
                $this->from->getContent()
            );

            $request->headers->replace($this->from->headers->all());

            $request->setJson($this->from->json());

            if ($session = $this->from->getSession()) {
                $request->setLaravelSession($session);
            }

            $request->setUserResolver($this->from->getUserResolver());

            $request->setRouteResolver($this->from->getRouteResolver());

            $this->request = $request;
        }

        return $this;
    }

    public function append(): PrettyUrlResolver
    {
        return $this;
    }

    public function run(): Request
    {
        return $this->request;
    }
}
