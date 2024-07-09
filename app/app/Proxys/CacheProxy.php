<?php

namespace App\Proxys;

use Illuminate\Support\Facades\Cache;

class CacheProxy
{
    public function get(string $key): mixed
    {
        return Cache::get($key);
    }

    public function put(string $key, mixed $value, int $seconds = 86400): mixed
    {
        return Cache::put($key, $value, $seconds);
    }

    public function remember(string $key, callable $callback, int $seconds = 86400): mixed
    {
        return Cache::remember($key, $seconds, $callback);
    }

    public function forget(string $key): CacheProxy
    {
        Cache::forget($key);
        return $this;
    }

    public function has(string $key): bool
    {
        return Cache::has($key);
    }
}
