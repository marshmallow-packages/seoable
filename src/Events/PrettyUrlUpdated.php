<?php

namespace Marshmallow\Seoable\Events;

use Illuminate\Queue\SerializesModels;
use Marshmallow\Seoable\Models\PrettyUrl;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class PrettyUrlUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $prettyUrl;

    public function __construct(PrettyUrl $prettyUrl)
    {
        $this->prettyUrl = $prettyUrl;
    }
}
