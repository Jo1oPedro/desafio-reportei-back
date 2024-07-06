<?php

namespace App\Services;

use App\DTO\PaginationDTO;
use App\DTO\RepositoryDTO;
use App\Helper\RepositoryCommitsAnalyzer;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class GithubService
{
    public function __construct(
        private RepositoryService $repository_service,
        private RepositoryCommitsAnalyzer $repository_commits_analyzer,
        private CacheService $cache_service,
    ) {}

    public function getUserRepositories(PaginationDTO $pagination, bool $cached = true)
    {
        if($cached) {
            $total_items = $this->cache_service->remember("totalRepositories", function () {
                return $this->getTotalRepositories();
            });
        } else {
            $total_items = $this->getTotalRepositories();
        }

        $response = $this->createHttpClient()
            ->get("https://api.github.com/user/repos?page={$pagination->getPage()}&per_page={$pagination->getPerPage()}&affiliation=owner");

        if($response->successful()) {
            return response()->json([
                "repositories" => $response->json(),
                "total_repositories" => $total_items
            ]);
        } else {
            return response()->json([
                "message" => "Failed to fetch repositories from Github"
            ], $response->status());
        }
    }

    public function getRepository(RepositoryDTO $repositoryDTO)
    {
        /** @todo adicionar cache com redis pro repositoryid */
        $repository = $this->repository_service->create($repositoryDTO);
        //if($repository->wasRecentlyCreated) {
            $response = $this->createHttpClient()
                ->get("https://api.github.com/repos/{$repositoryDTO->getOwnerName()}/{$repositoryDTO->getName()}/commits");
            //return $this->repository_commits_analyzer->analyzeRepositoryCommits($response->json());
        //}
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

    private function getTotalRepositories()
    {
        $response = $this->createHttpClient()
            ->get("https://api.github.com/user/repos?page=1&per_page=1&affiliation=owner", [
                "page" => 1,
                "per_page" => 1,
                "affiliation" => "owner"
            ]);
        $link = $response->headers()["Link"][0];
        preg_match('/<([^>]*)>; rel="last"/', $link, $matches);
        $last_url = $matches[1];
        preg_match('/page=(\d+)/', $last_url, $pageMatches);
        return $pageMatches[1];
    }
}
