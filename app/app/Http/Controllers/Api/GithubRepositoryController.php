<?php

namespace App\Http\Controllers\Api;
use App\Services\GithubService;
use Illuminate\Support\Facades\Http;

class GithubRepositoryController
{
    public function __construct(
        private GithubService $githubService
    ) {}

    public function index()
    {
        $user = auth()->user();
        return $this->githubService->getUserRepositories($user->access_token);
    }
}
