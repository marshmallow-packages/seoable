<?php

namespace Marshmallow\Seoable\Rules;

use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Rule;

class IsFullLocalUrl implements Rule
{
    public function passes($attribute, $value)
    {
        return Str::startsWith($value, $this->getStartPath());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The provided URL should start with :url', [
            'url' => $this->getStartPath(),
        ]);
    }

    protected function getStartPath()
    {
        $url = config('app.url');
        if (!Str::endsWith($url, '/')) {
            $url .= '/';
        }
        return $url;
    }
}
