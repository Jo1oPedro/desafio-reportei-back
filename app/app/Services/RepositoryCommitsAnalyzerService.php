<?php

namespace App\Services;

use App\DTO\CommitDTO;
use App\Proxys\CacheProxy;
use Carbon\Carbon;
use Laravel\Octane\Facades\Octane;

class RepositoryCommitsAnalyzerService
{
    public function __construct(
        private CacheProxy $cache_proxy,
        private CommitService $commit_service
    ) {}

    public function analyzeRepositoryCommits(string $repository_id, array ...$jsons)
    {
        $tasksConcurrently = [];
        $tasks = [];
        foreach ($jsons as $json) {
            if(count($json) === 1) {
                $tasks[] = $json;
                continue;
            }
            $tasksConcurrently[] = fn() => $this->getTotalCommitsForEachDay($repository_id, $json);
        }

        $results = [];
        if(count($tasksConcurrently)) {
            $results = Octane::concurrently($tasksConcurrently);
        }

        foreach($tasks as $task) {
            $results[] = $this->getTotalCommitsForEachDay($repository_id, $task);
        }

        $total_commits_per_day = $this->generateLast90Days();
        foreach($results as $result) {
            foreach($result as $date => $total_commits) {
                $total_commits_per_day[$date] += $total_commits;
            }
        }

        ksort($total_commits_per_day);
        $this->cacheResult($repository_id, $total_commits_per_day);

        return $total_commits_per_day;
    }

    public function getTotalCommitsForEachDay(string $repository_id, array $json)
    {
        $total_commits = [];
        foreach($json as $data) {
            $commit_date = Carbon::parse($data["commit"]["committer"]["date"])
                ->format("ymd");
            $this->commit_service->create(new CommitDTO(
                $data["node_id"],
                $data["author"]["login"],
                $data["author"]["id"],
                $repository_id,
                $commit_date
            ));
            if(array_key_exists($commit_date, $total_commits)) {
                $total_commits[$commit_date] += 1;
                continue;
            }
            $total_commits[$commit_date] = 1;
        }
        return $total_commits;
    }

    private function generateLast90Days(): array
    {
        $days_array = [];
        $start_date = Carbon::today();
        for($i = 0; $i <= 90; $i++) {
            $dateKey = $start_date->copy()->subDays($i)->format("ymd");
            $days_array[$dateKey] = 0;
        }

        return $days_array;
    }

    private function cacheResult(string $repository_id, array $total_commit_per_day)
    {
        $current_date_time = Carbon::now();
        $end_of_day = $current_date_time->copy()->endOfDay();
        $seconds_till_end_of_day = $current_date_time->diffInSeconds($end_of_day);
        $this->cache_proxy->put(
            $repository_id,
            $total_commit_per_day,
            $seconds_till_end_of_day
        );
    }
}
