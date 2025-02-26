<?php

namespace App\Services;

use App\Proxys\CacheProxy;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class GithubService
{
    public function __construct(
        private RepositoryCommitsAnalyzerService $repository_commits_analyzer_service,
        private CacheProxy $cache_proxy,
        private DateService $date_service
    ) {}

    public function getUserRepositories(string $page, string $per_page, string $cached)
    {
        $httpClient = $this->createHttpClient();
        $user_id = auth()->user()->id;
        if($cached !== "no-cache") {
            $total_public_repositories = $this->cache_proxy->remember("{$user_id}_totalRepositories", function () use ($httpClient) {
                $response = $httpClient->get("https://api.github.com/user");
                return $response->json("public_repos");
            });
        } else {
            $response = $httpClient->get("https://api.github.com/user");
            $total_public_repositories = $response->json("public_repos");
            $this->cache_proxy->forget("{$user_id}_totalRepositories")->put("{$user_id}_totalRepositories", $total_public_repositories);
        }

        $response = $httpClient
            ->get("https://api.github.com/user/repos", [
                "page" => $page,
                "per_page" => $per_page,
                "affiliation" => "owner"
            ]);

        if(!$response->successful()) {
            return response()->json([
                "message" => $response->json("message")
            ], $response->status());
        }

        try {
            $total_pages_number = $this->getTotalPagesNumber($response->headers()["Link"][0]);
        } catch (\Exception $error) {
            $total_pages_number = $page;
        }

        return response()->json([
            "repositories" => $response->json(),
            "total_public_repositories" => $total_public_repositories,
            "total_pages_number" => $total_pages_number,
        ]);
    }

    public function getRepository(string $owner_name, string $repository_name)
    {
        /** @todo adicionar cache com redis pro repositoryid */
        $response = $this->createHttpClient()
            ->get("https://api.github.com/repos/{$owner_name}/{$repository_name}");

        if(!$response->successful()) {
            return response()->json(
                ["message" => $response->json("message")],
                $response->status()
            );
        }

        if($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                "message" => "Failed to fetch repositories from Github"
            ], $response->status());
        }
    }

    public function getRepositoryCommits(string $owner_name, string $repository_name, string $repository_id, string $cache)
    {
        if($cache !== "no-cache" && $this->cache_proxy->has($repository_id)) {
            return response()->json($this->cache_proxy->get($repository_id));
        };
        $since = $this->date_service->since(90);
        $per_page = 100;
        $response = $this->createHttpClient()
            ->get("https://api.github.com/repos/{$owner_name}/{$repository_name}/commits", [
                "page" => 1,
                "per_page" => $per_page,
                "since" => $since
            ]);

        if(!$response->successful()) {
            return response()->json(
                ["message" => $response->json("message")],
                $response->status()
            );
        }

        try {
            $total_page_numbers = (int) $this->getTotalPagesNumber($response->headers()["Link"][0]);
        } catch (\Exception $error) {
            $total_page_numbers = 1;
        }

        $commits_urls = [];
        if($total_page_numbers > 1) {
            for($page = 2; $page <= $total_page_numbers; $page++) {
                $commits_urls[] = "https://api.github.com/repos/{$owner_name}/{$repository_name}/commits?page={$page}&per_page=$per_page&since={$since}";
            }

            $responses = $this->createHttpClient()->pool(function (Pool $pool) use ($commits_urls) {
                return collect($commits_urls)
                    ->map(
                        fn(string $url) => $pool->get($url)
                    );
            });

            $results = collect($responses)
                ->map(
                    fn(Response $response) => $response->successful() ? $response->json() : $response->headers()
                );
        }

        return response()->json(
            $this->repository_commits_analyzer_service
                ->analyzeRepositoryCommits($repository_id, $response->json(), ...$results?->toArray() ?? [])
        );
    }

    private function createHttpClient(): PendingRequest
    {
        $access_token = auth()->user()->access_token;
        return Http::withHeaders([
            "Authorization" => "Bearer {$access_token}",
            "Accept" => "application/json"
        ]);
    }

    private function getTotalRepositoriesNumber()
    {
        $response = $this->createHttpClient()
            ->get("https://api.github.com/user/repos", [
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

    private function getTotalPagesNumber(string $link)
    {
        preg_match('/<([^>]*)>; rel="last"/', $link, $matches);
        $last_url = $matches[1];
        preg_match('/page=(\d+)/', $last_url, $pageMatches);
        return $pageMatches[1];
    }
}
