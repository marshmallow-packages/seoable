<?php

namespace Marshmallow\Seoable\Objects;

class Robots
{
    protected $content;

    public function output()
    {
        return response($this->content, 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function userAgent($user_agent)
    {
        return $this->addRow('User-agent', $user_agent);
    }

    public function sitemap($sitemap)
    {
        return $this->addRow('Sitemap', $sitemap);
    }

    public function allow($regex)
    {
        return $this->addRow('Allow', $regex);
    }

    public function disallow($regex)
    {
        return $this->addRow('Disallow', $regex);
    }

    public function addRow($key, $value)
    {
        $this->content .= "{$key}: {$value}\n";
        return $this;
    }
}
