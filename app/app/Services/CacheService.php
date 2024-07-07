<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    public function put($key, $value, $seconds = 86400): mixed
    {
        return Cache::put($key, $value, $seconds);
    }

    public function remember(string $key, callable $callback, int $seconds = 86400): mixed
    {
        return Cache::remember($key, $seconds, $callback);
    }

    public function forget($key): CacheService
    {
        Cache::forget($key);
        return $this;
    }
}
