<?php

namespace App\Http\Controllers\Api;
use App\Services\GithubService;
use Illuminate\Http\Request;

class GithubRepositoryController
{
    public function __construct(
        private GithubService $github_service
    ) {}

    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 5);
        $cache = (bool) $request->input("cache", true);

        return $this->github_service->getUserRepositories(
            $page,
            $per_page,
            $cache
        );
    }

    public function show(string $repository_name)
    {
        return $this->github_service->getRepository(
            auth()->user()->github_login,
            $repository_name
        );
    }
}
