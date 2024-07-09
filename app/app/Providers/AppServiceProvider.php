<?php

namespace App\Providers;

use App\Proxys\CacheProxy;
use App\Services\DateService;
use App\Services\GithubService;
use App\Services\RepositoryCommitsAnalyzerService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('github', function ($app) {
            return new GithubService(
                $app->make(RepositoryCommitsAnalyzerService::class),
                $app->make(CacheProxy::class),
                $app->make(DateService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
