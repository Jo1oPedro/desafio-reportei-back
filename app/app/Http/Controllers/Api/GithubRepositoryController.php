<?php

namespace App\Http\Controllers\Api;
use App\DTO\PaginationDTO;
use App\DTO\RepositoryDTO;
use App\Services\GithubService;
use Illuminate\Http\Request;

class GithubRepositoryController
{
    public function __construct(
        private GithubService $github_service
    ) {}

    public function index(Request $request)
    {
        $pagination = new PaginationDTO(
            $request->input('page', 1),
            $request->input('per_page', 5)
        );

        $cache = (bool) $request->input("cache", true);

        return $this->github_service->getUserRepositories($pagination, $cache);
    }

    public function show(Request $request, string $repository_name)
    {
        $repository = new RepositoryDTO(
            $repository_name,
            auth()->user()->github_login,
            auth()->user()->id
        );

        return $this->github_service->getRepository($repository);
    }
}
