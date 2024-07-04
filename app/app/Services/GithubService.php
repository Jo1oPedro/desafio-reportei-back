<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GithubService
{
    public function getUserRepositories(Pagination $pagination)
    {
        $access_token = auth()->user()->access_token;
        $response = Http::withHeaders(
            [
                "Authorization" => "Bearer {$access_token}",
                "Accept" => "application/json"
            ])
            ->get("https://api.github.com/user/repos?page={$pagination->getPage()}&per_page={$pagination->getPerPage()}&affiliation=owner");

        if($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                "message" => "Failed to fetch repositories from Github"
            ], $response->status());
        }
    }
}
