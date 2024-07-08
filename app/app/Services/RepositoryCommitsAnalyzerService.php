<?php

namespace App\Services;

use App\DTO\CommitDTO;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Octane\Facades\Octane;

class RepositoryCommitsAnalyzerService
{
    public function __construct(
        private CacheService $cacheService,
        private CommitService $commit_service
    ) {}

    public function analyzeRepositoryCommits(string $repository_id, array ...$jsons)
    {
        $tasks = [];
        foreach ($jsons as $json) {
            $tasks[] = fn() => $this->getTotalCommitsForEachDay($repository_id, $json);
        }

        $results = [];
        DB::transaction(function () use (&$results, $tasks) {
            $results = Octane::concurrently($tasks);
        });

        $total_commit_per_day = [];
        foreach($results as $result) {
            foreach($result as $date => $total_commits) {
                if(array_key_exists($date, $total_commit_per_day)) {
                    $total_commit_per_day[$date] += $total_commits;
                    continue;
                }
                $total_commit_per_day[$date] = $total_commits;
            }
        }
        ksort($total_commit_per_day);
        $current_date_time = Carbon::now();
        $endOfDay = $current_date_time->copy()->endOfDay();
        $seconds_till_end_of_day = $current_date_time->diffInSeconds($endOfDay);
        $this->cacheService->put(
            $repository_id,
            $total_commit_per_day,
            $seconds_till_end_of_day
        );
        return $total_commit_per_day;
    }

    private function getTotalCommitsForEachDay(string $repository_id, array $json)
    {
        $totalCommits = [];
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
            if(array_key_exists($commit_date, $totalCommits)) {
                $totalCommits[$commit_date] += 1;
                continue;
            }
            $totalCommits[$commit_date] = 1;
        }
        return $totalCommits;
    }

}
