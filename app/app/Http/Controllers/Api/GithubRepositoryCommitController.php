<?php

namespace App\Http\Controllers\Api;

use App\Services\GithubService;

class GithubRepositoryCommitController
{
    public function __construct(
        private GithubService $github_service
    ) {}

    public function show(string $owner_name, string $repository_name, string $repository_id)
    {
        return $this->github_service->getRepositoryCommits(
            $owner_name,
            $repository_name,
            $repository_id
        );
    }
}
