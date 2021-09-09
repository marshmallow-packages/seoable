<?php

namespace Marshmallow\Seoable\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Marshmallow\Seoable\Facades\Seo;
use Illuminate\Database\Eloquent\Model;
use Marshmallow\Seoable\Traits\Seoable;
use Illuminate\Database\Eloquent\Builder;
use Marshmallow\Seoable\Events\PrettyUrlCreated;
use Marshmallow\Seoable\Events\PrettyUrlUpdated;
use Marshmallow\Nova\Flexible\Casts\FlexibleCast;

class PrettyUrl extends Model
{
    use Seoable;

    protected $guarded = [];

    protected $casts = [
        'seoable_content' => FlexibleCast::class,
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(
            function (PrettyUrl $prettyUrl) {
                $prettyUrl->cleanUrls();
            }
        );

        static::created(
            function (PrettyUrl $prettyUrl) {
                event(new PrettyUrlCreated($prettyUrl));
            }
        );

        static::updating(
            function (PrettyUrl $prettyUrl) {
                $prettyUrl->cleanUrls();
            }
        );

        static::updated(
            function (PrettyUrl $prettyUrl) {
                if ($prettyUrl->isDirty('original_url')) {
                    event(new PrettyUrlUpdated($prettyUrl));
                }
            }
        );
    }

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

    public function setSeoableContent($use_for_seo = false): self
    {
        if ($use_for_seo && $this->seoable) {
            Seo::set($this, true);
        }

        Seo::setSeoableContent($this->seoable_content);
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
        $path = Str::of($path);
        $full_path = Str::of($this->getFullDomainPath());

        if ($full_path->endsWith('/')) {
            $full_path = $full_path->limit(
                $full_path->length() - 1,
                ''
            );
        }

        if (!$path->startsWith('/')) {
            $full_path = $full_path->append('/');
        }

        return $full_path->append($path);
    }

    protected function getFullDomainPath(): string
    {
        $url = Str::of(config('app.url'));
        if (!$url->endsWith('/')) {
            $url = $url->append('/');
        }
        return $url;
    }

    public function cleanUrls()
    {
        $this->original_url = $this->cleanUrl('original_url');
        $this->pretty_url = $this->cleanUrl('pretty_url');
    }

    public function cleanUrl($column)
    {
        $url = $this->{$column};
        $url = explode('#', $url);
        return $url[0];
    }

    public function getOriginalRouteJsonFromUrl()
    {
        $original = $this->original_url;
        $original = explode('#', $original);
        $original = Str::of($original[0]);

        if ($original->contains('?')) {
            $original = $original->append('&prettier_route_response');
        } else {
            $original = $original->append('?prettier_route_response');
        }

        $response = Http::withoutVerifying()->get($original);
        return $response->json();
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
