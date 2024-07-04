<?php

namespace App\Http\Controllers\Api;
use App\Services\GithubService;
use App\Services\Pagination;
use Illuminate\Http\Request;

class GithubRepositoryController
{
    public function __construct(
        private GithubService $githubService
    ) {}

    public function index(Request $request)
    {
        $pagination = new Pagination(
            $request->input('page', 1),
            $request->input('per_page', 10)
        );
        return $this->githubService->getUserRepositories($pagination);
    }
}
