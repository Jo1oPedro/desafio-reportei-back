<?php

namespace App\Services;

use App\DTO\PaginationDTO;
use App\DTO\RepositoryDTO;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class GithubService
{
    public function __construct(
        private RepositoryService $repository_service
    ) {}

    public function getUserRepositories(PaginationDTO $pagination)
    {
        $response = $this->createHttpClient()
            ->get("https://api.github.com/user/repos?page={$pagination->getPage()}&per_page={$pagination->getPerPage()}&affiliation=owner");

        if($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                "message" => "Failed to fetch repositories from Github"
            ], $response->status());
        }
    }

    public function getRepository(RepositoryDTO $repositoryDTO)
    {
        $this->repository_service->create($repositoryDTO);
        $response = $this->createHttpClient()
            ->get("https://api.github.com/repos/{$repositoryDTO->getOwnerName()}/{$repositoryDTO->getName()}/activity");

        if($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                "message" => "Failed to fetch repositories from Github"
            ], $response->status());
        }
    }

    private function createHttpClient(): PendingRequest
    {
        $access_token = auth()->user()->access_token;
        return Http::withHeaders([
            "Authorization" => "Bearer {$access_token}",
            "Accept" => "application/json"
        ]);
    }
}
