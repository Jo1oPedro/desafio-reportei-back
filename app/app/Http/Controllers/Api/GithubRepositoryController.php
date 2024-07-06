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
        return $this->github_service->getUserRepositories($pagination);
    }

    public function show(Request $request, string $owner_name, string $name, string $repository_id)
    {
        $repository = new RepositoryDTO(
            $name,
            $owner_name,
            auth()->user()->id,
            $repository_id
        );

        return $this->github_service->getRepository($repository);
    }
}
