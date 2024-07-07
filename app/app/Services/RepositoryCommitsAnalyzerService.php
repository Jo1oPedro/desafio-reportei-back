<?php

namespace App\Services;

use Carbon\Carbon;
use Laravel\Octane\Facades\Octane;

class RepositoryCommitsAnalyzerService
{
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

    public function getTotalCommitsForEachDay(array $json)
    {
        $totalCommits = [];
        foreach($json as $data) {
            $commit_date = Carbon::parse($data["commit"]["committer"]["date"])
                ->format("dmy");
            if(array_key_exists($commit_date, $totalCommits)) {
                $totalCommits[$commit_date] += 1;
                continue;
            }
            $totalCommits[$commit_date] = 1;
        }
        return $totalCommits;
    }

}
