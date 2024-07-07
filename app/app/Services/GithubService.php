<?php

namespace App\Services;

use App\DTO\PaginationDTO;
use App\DTO\RepositoryDTO;
use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class GithubService
{
    public function __construct(
        private RepositoryService $repository_service,
        private RepositoryCommitsAnalyzerService $repository_commits_analyzer_service,
        private CacheService $cache_service,
        private DateService $date_service
    ) {}

    public function getUserRepositories(PaginationDTO $pagination, bool $cached = true)
    {
        $httpClient = $this->createHttpClient();
        if($cached) {
            $total_public_repositories = $this->cache_service->remember("totalRepositories", function () use ($httpClient) {
                $response = $httpClient->get("https://api.github.com/user");
                return $response->json("public_repos");
            });
        } else {
            $response = $httpClient->get("https://api.github.com/user");
            $total_public_repositories = $response->json("public_repos");
            $this->cache_service->forget("totalRepositories")->put("totalRepositories", $total_public_repositories);
        }

        $response = $httpClient
            ->get("https://api.github.com/user/repos", [
                "page" => $pagination->getPage(),
                "per_page" => $pagination->getPerPage(),
                "affiliation" => "owner"
            ]);

        try {
            $total_pages_number = $this->getTotalPagesNumber($response->headers()["Link"][0]);
        } catch (\Exception $error) {
            $total_pages_number = $pagination->getPage();
        }

        if($response->successful()) {
            return response()->json([
                "repositories" => $response->json(),
                "total_public_repositories" => $total_public_repositories,
                "total_pages_number" => $total_pages_number,
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
        $since = $this->date_service->since(90);
        $response = $this->createHttpClient()
            ->get("https://api.github.com/repos/{$repositoryDTO->getOwnerName()}/{$repositoryDTO->getName()}/commits", [
                "page" => 1,
                "per_page" => 1,
                //"since" => $since
            ]);

        try {
            $total_page_numbers = (int) $this->getTotalPagesNumber($response->headers()["Link"][0]);
        } catch (\Exception $error) {
            $total_page_numbers = 1;
        }

        $commits_urls = [];
        if($total_page_numbers > 1) {
            for($page = 2; $page <= $total_page_numbers; $page++) {
                $commits_urls[] = "https://api.github.com/repos/{$repositoryDTO->getOwnerName()}/{$repositoryDTO->getName()}/commits?page={$page}&per_page=1";
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

        //return response()->json($results);
        /*$array1 = [2, 4, 6, 8, 10];
        $array2 = [1, 3, 5, 7, 9];
        $array3 = [0, 2, 4, 6, 8];
        $results = Octane::concurrently([
            fn() => array_sum($array1),
            fn() => array_sum($array2),
            fn() => array_sum($array3),
        ]);
        $results = array_sum($results);*/

        $commits = $response->json();
        foreach($results?->toArray() ?? [] as $result) {
            $commits = array_merge($commits, $result);
        }
        return response()->json(
            $this->repository_commits_analyzer_service
                ->analyzeRepositoryCommits($response->json(), ...$results?->toArray() ?? [])
        );
        return response()->json(
            Carbon::parse($commits[0]["commit"]["committer"]["date"])
                ->format("dmy")
        );
        return response()->json($commits);
            //return response()->json($responses);


        /*if($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                "message" => "Failed to fetch repositories from Github"
            ], $response->status());
        }*/
    }

    public function getRepositoryCommits(RepositoryDTO $repositoryDTO)
    {
        /** @todo adicionar cache com redis pro repositoryid */
        $repository = $this->repository_service->create($repositoryDTO);
        $since = $this->date_service->since(90);
        $response = $this->createHttpClient()
            ->get("https://api.github.com/repos/{$repositoryDTO->getOwnerName()}/{$repositoryDTO->getName()}/commits", [
                "page" => 1,
                "per_page" => 1,
                //"since" => $since
            ]);

        try {
            $total_page_numbers = (int) $this->getTotalPagesNumber($response->headers()["Link"][0]);
        } catch (\Exception $error) {
            $total_page_numbers = 1;
        }

        $commits_urls = [];
        if($total_page_numbers > 1) {
            for($page = 2; $page <= $total_page_numbers; $page++) {
                $commits_urls[] = "https://api.github.com/repos/{$repositoryDTO->getOwnerName()}/{$repositoryDTO->getName()}/commits?page={$page}&per_page=1";
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

        $commits = $response->json();
        foreach($results?->toArray() ?? [] as $result) {
            $commits = array_merge($commits, $result);
        }
        return response()->json(
            $this->repository_commits_analyzer_service
                ->analyzeRepositoryCommits($response->json(), ...$results?->toArray() ?? [])
        );
        return response()->json(
            Carbon::parse($commits[0]["commit"]["committer"]["date"])
                ->format("dmy")
        );
        return response()->json($commits);

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
