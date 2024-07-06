<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    public function remember(string $key, callable $callback, int $seconds = 86400)
    {
        return Cache::remember($key, $seconds, $callback);
    }
}
