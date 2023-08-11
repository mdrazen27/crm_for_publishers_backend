<?php

namespace App\Traits;

use App\Models\Scopes\FilterByPublisherScope;

trait FilterByPublisher
{

    public static function bootFilterByPublisher()
    {
        static::addGlobalScope(new FilterByPublisherScope);
    }
}
