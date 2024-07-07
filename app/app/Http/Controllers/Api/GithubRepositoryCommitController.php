<?php

namespace App\Http\Controllers\Api;

use App\DTO\RepositoryDTO;
use App\Services\GithubService;
use Illuminate\Http\Request;

class GithubRepositoryCommitController
{
    public function __construct(
        private GithubService $github_service
    ) {}

    public function show(Request $request, string $owner_name, string $name, string $repository_id)
    {
        $repository = new RepositoryDTO(
            $name,
            $owner_name,
            auth()->user()->id,
            $repository_id
        );

        return $this->github_service->getRepositoryCommits($repository);
    }
}
