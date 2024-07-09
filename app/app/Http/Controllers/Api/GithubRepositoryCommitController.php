<?php

namespace App\Http\Controllers\Api;

use App\Services\GithubService;
use Illuminate\Http\Request;

class GithubRepositoryCommitController
{
    public function __construct(
        private GithubService $github_service
    ) {}

    /**
     * @OA\Get(
     *      path="/api/github/repository/commits/{owner_name}/{repository_name}/{repository_id}",
     *      tags={"User's repository commits"},
     *      summary="Get user's repository commits",
     *      description="This endpoint returns a specified user repository commits",
     *      operationId="getUserRepositoryCommits",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *           name="Cache-Control",
     *           in="header",
     *           description="Cache control header",
     *           required=false,
     *           @OA\Schema(
     *               type="string",
     *               enum={"cache", "no-cache"}
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="repository_name",
     *           in="path",
     *           description="The name of the repository",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="repository_id",
     *           in="path",
     *           description="The id of the repository",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *      @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="240411", type="integer", example=0),
     *               @OA\Property(property="240412", type="integer", example=0),
     *               @OA\Property(property="240413", type="integer", example=0),
     *               @OA\Property(property="240414", type="integer", example=0),
     *               @OA\Property(property="240415", type="integer", example=0),
     *               @OA\Property(property="240416", type="integer", example=0),
     *               @OA\Property(property="240417", type="integer", example=0),
     *               @OA\Property(property="240418", type="integer", example=0),
     *               @OA\Property(property="240419", type="integer", example=0),
     *               @OA\Property(property="240420", type="integer", example=0),
     *               @OA\Property(property="240421", type="integer", example=0),
     *               @OA\Property(property="240422", type="integer", example=0),
     *               @OA\Property(property="240423", type="integer", example=0),
     *               @OA\Property(property="240424", type="integer", example=0),
     *               @OA\Property(property="240425", type="integer", example=0),
     *               @OA\Property(property="240426", type="integer", example=0),
     *               @OA\Property(property="240427", type="integer", example=0),
     *               @OA\Property(property="240428", type="integer", example=0),
     *               @OA\Property(property="240429", type="integer", example=0),
     *               @OA\Property(property="240430", type="integer", example=0),
     *               @OA\Property(property="240501", type="integer", example=0),
     *               @OA\Property(property="240502", type="integer", example=0),
     *               @OA\Property(property="240503", type="integer", example=0),
     *               @OA\Property(property="240504", type="integer", example=0),
     *               @OA\Property(property="240505", type="integer", example=0),
     *               @OA\Property(property="240506", type="integer", example=0),
     *               @OA\Property(property="240507", type="integer", example=0),
     *               @OA\Property(property="240508", type="integer", example=0),
     *               @OA\Property(property="240509", type="integer", example=0),
     *               @OA\Property(property="240510", type="integer", example=0),
     *               @OA\Property(property="240511", type="integer", example=0),
     *               @OA\Property(property="240512", type="integer", example=0),
     *               @OA\Property(property="240513", type="integer", example=0),
     *               @OA\Property(property="240514", type="integer", example=0),
     *               @OA\Property(property="240515", type="integer", example=0),
     *               @OA\Property(property="240516", type="integer", example=0),
     *               @OA\Property(property="240517", type="integer", example=0),
     *               @OA\Property(property="240518", type="integer", example=0),
     *               @OA\Property(property="240519", type="integer", example=0),
     *               @OA\Property(property="240520", type="integer", example=0),
     *               @OA\Property(property="240521", type="integer", example=0),
     *               @OA\Property(property="240522", type="integer", example=0),
     *               @OA\Property(property="240523", type="integer", example=0),
     *               @OA\Property(property="240524", type="integer", example=0),
     *               @OA\Property(property="240525", type="integer", example=0),
     *               @OA\Property(property="240526", type="integer", example=0),
     *               @OA\Property(property="240527", type="integer", example=0),
     *               @OA\Property(property="240528", type="integer", example=0),
     *               @OA\Property(property="240529", type="integer", example=0),
     *               @OA\Property(property="240530", type="integer", example=0),
     *               @OA\Property(property="240531", type="integer", example=0),
     *               @OA\Property(property="240601", type="integer", example=0),
     *               @OA\Property(property="240602", type="integer", example=0),
     *               @OA\Property(property="240603", type="integer", example=0),
     *               @OA\Property(property="240604", type="integer", example=0),
     *               @OA\Property(property="240605", type="integer", example=0),
     *               @OA\Property(property="240606", type="integer", example=0),
     *               @OA\Property(property="240607", type="integer", example=0),
     *               @OA\Property(property="240608", type="integer", example=0),
     *               @OA\Property(property="240609", type="integer", example=0),
     *               @OA\Property(property="240610", type="integer", example=0),
     *               @OA\Property(property="240611", type="integer", example=0),
     *               @OA\Property(property="240612", type="integer", example=0),
     *               @OA\Property(property="240613", type="integer", example=0),
     *               @OA\Property(property="240614", type="integer", example=0),
     *               @OA\Property(property="240615", type="integer", example=0),
     *               @OA\Property(property="240616", type="integer", example=0),
     *               @OA\Property(property="240617", type="integer", example=0),
     *               @OA\Property(property="240618", type="integer", example=0),
     *               @OA\Property(property="240619", type="integer", example=0),
     *               @OA\Property(property="240620", type="integer", example=0),
     *               @OA\Property(property="240621", type="integer", example=0),
     *               @OA\Property(property="240622", type="integer", example=0),
     *               @OA\Property(property="240623", type="integer", example=0),
     *               @OA\Property(property="240624", type="integer", example=0),
     *               @OA\Property(property="240625", type="integer", example=0),
     *               @OA\Property(property="240626", type="integer", example=0),
     *               @OA\Property(property="240627", type="integer", example=0),
     *               @OA\Property(property="240628", type="integer", example=0),
     *               @OA\Property(property="240629", type="integer", example=0),
     *               @OA\Property(property="240630", type="integer", example=0),
     *               @OA\Property(property="240701", type="integer", example=0),
     *               @OA\Property(property="240702", type="integer", example=0),
     *               @OA\Property(property="240703", type="integer", example=23),
     *               @OA\Property(property="240704", type="integer", example=16),
     *               @OA\Property(property="240705", type="integer", example=11),
     *               @OA\Property(property="240706", type="integer", example=13),
     *               @OA\Property(property="240707", type="integer", example=9),
     *               @OA\Property(property="240708", type="integer", example=25),
     *               @OA\Property(property="240709", type="integer", example=4)
     *           )
     *       ),
     *       @OA\Response(
     *           response=409,
     *           description="Git repository is empty.",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="message", type="string", example="Git Repository is empty.")
     *           )
     *       )
     *  )
     */
    public function show(Request $request, string $repository_name, string $repository_id)
    {
        $cache = $request->header("Cache-Control", "cache");
        return $this->github_service->getRepositoryCommits(
            auth()->user()->github_login,
            $repository_name,
            $repository_id,
            $cache
        );
    }
}
