<?php

namespace App\Http\Controllers\Api;

use App\Services\GithubService;
use Illuminate\Http\Request;

class GithubRepositoryCommitController
{
    public function __construct(
        private GithubService $github_service
    ) {}

    public function show(Request $request, string $owner_name, string $repository_name, string $repository_id)
    {
        $cache = $request->header("Cache-Control", "cache");
        return $this->github_service->getRepositoryCommits(
            $owner_name,
            $repository_name,
            $repository_id,
            $cache
        );
    }
}
