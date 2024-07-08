<?php

namespace App\Services;

use App\DTO\CommitDTO;
use Carbon\Carbon;
use Laravel\Octane\Facades\Octane;

class RepositoryCommitsAnalyzerService
{
    public function __construct(
        private CommitService $commit_service
    ) {}

    public function analyzeRepositoryCommits(array ...$jsons)
    {
        $tasks = [];
        foreach ($jsons as $json) {
            $tasks[] = fn() => $this->getTotalCommitsForEachDay($json);
        }

        $results = Octane::concurrently($tasks);

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
        return $total_commit_per_day;
    }

    private function getTotalCommitsForEachDay(array $json)
    {
        $totalCommits = [];
        foreach($json as $data) {
            $commit_date = Carbon::parse($data["commit"]["committer"]["date"])
                ->format("ymd");
            $this->commit_service->create(new CommitDTO(
                $data["node_id"],
                $data["author"]["login"],
                $data["author"]["id"],
                1,
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
