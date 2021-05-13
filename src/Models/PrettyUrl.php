<?php

namespace Marshmallow\Seoable\Models;

use Illuminate\Support\Str;
use Marshmallow\Seoable\Facades\Seo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PrettyUrl extends Model
{
    public function getRelativePath(): string
    {
        return Str::replaceFirst(config('app.url'), '', $this->pretty_url);
    }

    public function shouldBeRedirected(): bool
    {
        return ($this->should_redirect);
    }

    public function shouldUseAsCanonical(): bool
    {
        return ($this->is_canonical);
    }

    public function checkAndSetCanonical(): self
    {
        if ($this->shouldUseAsCanonical()) {
            $this->setCanonical();
        }
        return $this;
    }

    public function setCanonical(): self
    {
        Seo::setSeoCanonicalUrl($this->pretty_url);
        return $this;
    }

    public function getRedirectToPretty()
    {
        return redirect()->to($this->pretty_url);
    }

    public function getCleanPath(string $column): string
    {
        return Str::replaceFirst($this->getFullDomainPath(), '', $this->{$column});
    }

    protected function buildFullUrl($path): string
    {
        return Str::of($this->getFullDomainPath())->append($path);
    }

    protected function getFullDomainPath(): string
    {
        $url = Str::of(config('app.url'));
        if (!$url->endsWith('/')) {
            $url = $url->append('/');
        }
        return $url;
    }

    public function scopeByPath(Builder $builder, string $path): void
    {
        $builder->where('pretty_url', $this->buildFullUrl($path));
    }

    public function scopeByOriginalPath(Builder $builder, string $path): void
    {
        $builder->where('original_url', $this->buildFullUrl($path));
    }
}
