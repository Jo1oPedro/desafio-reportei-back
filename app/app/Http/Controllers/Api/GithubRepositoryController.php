<?php

namespace App\Http\Controllers\Api;
use App\Facades\Github;
use Illuminate\Http\Request;

class GithubRepositoryController
{
    /**
     * @OA\Get(
     *      path="/api/github/repositories",
     *      tags={"User's repositories"},
     *      summary="Get user's repositories",
     *      description="This endpoint returns all public user's repositories",
     *      operationId="getUserRepositories",
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
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Page number",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              default=1
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Number of items per page",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              default=5
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="id", type="integer", example=9999999999),
     *                  @OA\Property(property="node_id", type="string", example="Q_agDafert8g"),
     *                  @OA\Property(property="name", type="string", example="desafio-reportei-back"),
     *                  @OA\Property(property="full_name", type="string", example="Jo1oPedro/desafio-reportei-back"),
     *                  @OA\Property(property="private", type="boolean", example=false),
     *                  @OA\Property(
     *                      property="owner",
     *                      type="object",
     *                      @OA\Property(property="login", type="string", example="Jo1oPedro"),
     *                      @OA\Property(property="id", type="integer", example=9999999999),
     *                      @OA\Property(property="node_id", type="string", example="MDQ6TQHlcjg8asLwODI6"),
     *                      @OA\Property(property="avatar_url", type="string", example="https://avatars.githubusercontent.com/u/9999999999?v=4"),
     *                      @OA\Property(property="gravatar_id", type="string", example=""),
     *                      @OA\Property(property="url", type="string", example="https://api.github.com/users/Jo1oPedro"),
     *                      @OA\Property(property="html_url", type="string", example="https://github.com/Jo1oPedro"),
     *                      @OA\Property(property="followers_url", type="string", example="https://api.github.com/users/Jo1oPedro/followers"),
     *                      @OA\Property(property="following_url", type="string", example="https://api.github.com/users/Jo1oPedro/following{/other_user}"),
     *                      @OA\Property(property="gists_url", type="string", example="https://api.github.com/users/Jo1oPedro/gists{/gist_id}"),
     *                      @OA\Property(property="starred_url", type="string", example="https://api.github.com/users/Jo1oPedro/starred{/owner}{/repo}"),
     *                      @OA\Property(property="subscriptions_url", type="string", example="https://api.github.com/users/Jo1oPedro/subscriptions"),
     *                      @OA\Property(property="organizations_url", type="string", example="https://api.github.com/users/Jo1oPedro/orgs"),
     *                      @OA\Property(property="repos_url", type="string", example="https://api.github.com/users/Jo1oPedro/repos"),
     *                      @OA\Property(property="events_url", type="string", example="https://api.github.com/users/Jo1oPedro/events{/privacy}"),
     *                      @OA\Property(property="received_events_url", type="string", example="https://api.github.com/users/Jo1oPedro/received_events"),
     *                      @OA\Property(property="type", type="string", example="User"),
     *                      @OA\Property(property="site_admin", type="boolean", example=false)
     *                  ),
     *                  @OA\Property(property="html_url", type="string", example="https://github.com/Jo1oPedro/desafio-reportei-back"),
     *                  @OA\Property(property="description", type="string", example=null),
     *                  @OA\Property(property="fork", type="boolean", example=false),
     *                  @OA\Property(property="url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back"),
     *                  @OA\Property(property="forks_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/forks"),
     *                  @OA\Property(property="keys_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/keys{/key_id}"),
     *                  @OA\Property(property="collaborators_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/collaborators{/collaborator}"),
     *                  @OA\Property(property="teams_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/teams"),
     *                  @OA\Property(property="hooks_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/hooks"),
     *                  @OA\Property(property="issue_events_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/issues/events{/number}"),
     *                  @OA\Property(property="events_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/events"),
     *                  @OA\Property(property="assignees_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/assignees{/user}"),
     *                  @OA\Property(property="branches_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/branches{/branch}"),
     *                  @OA\Property(property="tags_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/tags"),
     *                  @OA\Property(property="blobs_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/git/blobs{/sha}"),
     *                  @OA\Property(property="git_tags_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/git/tags{/sha}"),
     *                  @OA\Property(property="git_refs_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/git/refs{/sha}"),
     *                  @OA\Property(property="trees_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/git/trees{/sha}"),
     *                  @OA\Property(property="statuses_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/statuses/{sha}"),
     *                  @OA\Property(property="languages_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/languages"),
     *                  @OA\Property(property="stargazers_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/stargazers"),
     *                  @OA\Property(property="contributors_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/contributors"),
     *                  @OA\Property(property="subscribers_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/subscribers"),
     *                  @OA\Property(property="subscription_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/subscription"),
     *                  @OA\Property(property="commits_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/commits{/sha}"),
     *                  @OA\Property(property="git_commits_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/git/commits{/sha}"),
     *                  @OA\Property(property="comments_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/comments{/number}"),
     *                  @OA\Property(property="issue_comment_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/issues/comments{/number}"),
     *                  @OA\Property(property="contents_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/contents/{+path}"),
     *                  @OA\Property(property="compare_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/compare/{base}...{head}"),
     *                  @OA\Property(property="merges_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/merges"),
     *                  @OA\Property(property="archive_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/{archive_format}{/ref}"),
     *                  @OA\Property(property="downloads_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/downloads"),
     *                  @OA\Property(property="issues_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/issues{/number}"),
     *                  @OA\Property(property="pulls_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/pulls{/number}"),
     *                  @OA\Property(property="milestones_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/milestones{/number}"),
     *                  @OA\Property(property="notifications_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/notifications{?since,all,participating}"),
     *                  @OA\Property(property="labels_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/labels{/name}"),
     *                  @OA\Property(property="releases_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/releases{/id}"),
     *                  @OA\Property(property="deployments_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/deployments"),
     *                  @OA\Property(property="created_at", type="string", example="2023-05-29T14:53:54Z"),
     *                  @OA\Property(property="updated_at", type="string", example="2023-05-29T14:53:54Z"),
     *                  @OA\Property(property="pushed_at", type="string", example="2023-06-03T14:16:44Z"),
     *                  @OA\Property(property="git_url", type="string", example="git://github.com/Jo1oPedro/desafio-reportei-back.git"),
     *                  @OA\Property(property="ssh_url", type="string", example="git@github.com:Jo1oPedro/desafio-reportei-back.git"),
     *                  @OA\Property(property="clone_url", type="string", example="https://github.com/Jo1oPedro/desafio-reportei-back.git"),
     *                  @OA\Property(property="svn_url", type="string", example="https://github.com/Jo1oPedro/desafio-reportei-back"),
     *                  @OA\Property(property="homepage", type="string", example=null),
     *                  @OA\Property(property="size", type="integer", example=67),
     *                  @OA\Property(property="stargazers_count", type="integer", example=0),
     *                  @OA\Property(property="watchers_count", type="integer", example=0),
     *                  @OA\Property(property="language", type="string", example="PHP"),
     *                  @OA\Property(property="has_issues", type="boolean", example=true),
     *                  @OA\Property(property="has_projects", type="boolean", example=true),
     *                  @OA\Property(property="has_downloads", type="boolean", example=true),
     *                  @OA\Property(property="has_wiki", type="boolean", example=true),
     *                  @OA\Property(property="has_pages", type="boolean", example=false),
     *                  @OA\Property(property="has_discussions", type="boolean", example=false),
     *                  @OA\Property(property="forks_count", type="integer", example=0),
     *                  @OA\Property(property="mirror_url", type="string", example=null),
     *                  @OA\Property(property="archived", type="boolean", example=false),
     *                  @OA\Property(property="disabled", type="boolean", example=false),
     *                  @OA\Property(property="open_issues_count", type="integer", example=0),
     *                  @OA\Property(property="license", type="object", nullable=true),
     *                  @OA\Property(property="allow_forking", type="boolean", example=true),
     *                  @OA\Property(property="is_template", type="boolean", example=false),
     *                  @OA\Property(property="web_commit_signoff_required", type="boolean", example=false),
     *                  @OA\Property(property="topics", type="array", @OA\Items(type="string")),
     *                  @OA\Property(property="visibility", type="string", example="public"),
     *                  @OA\Property(property="forks", type="integer", example=0),
     *                  @OA\Property(property="open_issues", type="integer", example=0),
     *                  @OA\Property(property="watchers", type="integer", example=0),
     *                  @OA\Property(property="default_branch", type="string", example="main"),
     *                  @OA\Property(property="permissions", type="object", @OA\Property(property="admin", type="boolean", example=true), @OA\Property(property="maintain", type="boolean", example=true), @OA\Property(property="push", type="boolean", example=true), @OA\Property(property="triage", type="boolean", example=true), @OA\Property(property="pull", type="boolean", example=true))
     *              )
     *          )
     *      ),
     * )
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 5);
        $cache = $request->header("Cache-Control", "cache");

        return Github::getUserRepositories(
            $page,
            $per_page,
            $cache
        );
    }

    /**
     * @OA\Get(
     *      path="/api/github/repository/{repository_name}",
     *      tags={"User's repositories"},
     *      summary="Get user's repository",
     *      description="This endpoint returns a specified user repository",
     *      operationId="getUserRepository",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="repository_name",
     *          in="path",
     *          description="The name of the repository",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example=9999999999),
     *              @OA\Property(property="node_id", type="string", example="Q_agDafert8g"),
     *              @OA\Property(property="name", type="string", example="desafio-reportei-back"),
     *              @OA\Property(property="full_name", type="string", example="Jo1oPedro/desafio-reportei-back"),
     *              @OA\Property(property="private", type="boolean", example=false),
     *              @OA\Property(
     *                  property="owner",
     *                  type="object",
     *                  @OA\Property(property="login", type="string", example="Jo1oPedro"),
     *                  @OA\Property(property="id", type="integer", example=9999999999),
     *                  @OA\Property(property="node_id", type="string", example="MDQ6TQHlcjg8asLwODI6"),
     *                  @OA\Property(property="avatar_url", type="string", example="https://avatars.githubusercontent.com/u/9999999999?v=4"),
     *                  @OA\Property(property="gravatar_id", type="string", example=""),
     *                  @OA\Property(property="url", type="string", example="https://api.github.com/users/Jo1oPedro"),
     *                  @OA\Property(property="html_url", type="string", example="https://github.com/Jo1oPedro"),
     *                  @OA\Property(property="followers_url", type="string", example="https://api.github.com/users/Jo1oPedro/followers"),
     *                  @OA\Property(property="following_url", type="string", example="https://api.github.com/users/Jo1oPedro/following{/other_user}"),
     *                  @OA\Property(property="gists_url", type="string", example="https://api.github.com/users/Jo1oPedro/gists{/gist_id}"),
     *                  @OA\Property(property="starred_url", type="string", example="https://api.github.com/users/Jo1oPedro/starred{/owner}{/repo}"),
     *                  @OA\Property(property="subscriptions_url", type="string", example="https://api.github.com/users/Jo1oPedro/subscriptions"),
     *                  @OA\Property(property="organizations_url", type="string", example="https://api.github.com/users/Jo1oPedro/orgs"),
     *                  @OA\Property(property="repos_url", type="string", example="https://api.github.com/users/Jo1oPedro/repos"),
     *                  @OA\Property(property="events_url", type="string", example="https://api.github.com/users/Jo1oPedro/events{/privacy}"),
     *                  @OA\Property(property="received_events_url", type="string", example="https://api.github.com/users/Jo1oPedro/received_events"),
     *                  @OA\Property(property="type", type="string", example="User"),
     *                  @OA\Property(property="site_admin", type="boolean", example=false)
     *              ),
     *              @OA\Property(property="html_url", type="string", example="https://github.com/Jo1oPedro/desafio-reportei-back"),
     *              @OA\Property(property="description", type="string", example=null),
     *              @OA\Property(property="fork", type="boolean", example=false),
     *              @OA\Property(property="url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back"),
     *              @OA\Property(property="forks_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/forks"),
     *              @OA\Property(property="keys_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/keys{/key_id}"),
     *              @OA\Property(property="collaborators_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/collaborators{/collaborator}"),
     *              @OA\Property(property="teams_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/teams"),
     *              @OA\Property(property="hooks_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/hooks"),
     *              @OA\Property(property="issue_events_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/issues/events{/number}"),
     *              @OA\Property(property="events_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/events"),
     *              @OA\Property(property="assignees_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/assignees{/user}"),
     *              @OA\Property(property="branches_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/branches{/branch}"),
     *              @OA\Property(property="tags_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/tags"),
     *              @OA\Property(property="blobs_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/git/blobs{/sha}"),
     *              @OA\Property(property="git_tags_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/git/tags{/sha}"),
     *              @OA\Property(property="git_refs_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/git/refs{/sha}"),
     *              @OA\Property(property="trees_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/git/trees{/sha}"),
     *              @OA\Property(property="statuses_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/statuses/{sha}"),
     *              @OA\Property(property="languages_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/languages"),
     *              @OA\Property(property="stargazers_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/stargazers"),
     *              @OA\Property(property="contributors_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/contributors"),
     *              @OA\Property(property="subscribers_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/subscribers"),
     *              @OA\Property(property="subscription_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/subscription"),
     *              @OA\Property(property="commits_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/commits{/sha}"),
     *              @OA\Property(property="git_commits_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/git/commits{/sha}"),
     *              @OA\Property(property="comments_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/comments{/number}"),
     *              @OA\Property(property="issue_comment_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/issues/comments{/number}"),
     *              @OA\Property(property="contents_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/contents/{+path}"),
     *              @OA\Property(property="compare_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/compare/{base}...{head}"),
     *              @OA\Property(property="merges_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/merges"),
     *              @OA\Property(property="archive_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/{archive_format}{/ref}"),
     *              @OA\Property(property="downloads_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/downloads"),
     *              @OA\Property(property="issues_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/issues{/number}"),
     *              @OA\Property(property="pulls_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/pulls{/number}"),
     *              @OA\Property(property="milestones_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/milestones{/number}"),
     *              @OA\Property(property="notifications_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/notifications{?since,all,participating}"),
     *              @OA\Property(property="labels_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/labels{/name}"),
     *              @OA\Property(property="releases_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/releases{/id}"),
     *              @OA\Property(property="deployments_url", type="string", example="https://api.github.com/repos/Jo1oPedro/desafio-reportei-back/deployments"),
     *              @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-03T20:32:31Z"),
     *              @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-08T22:11:45Z"),
     *              @OA\Property(property="pushed_at", type="string", format="date-time", example="2024-07-08T22:11:41Z"),
     *              @OA\Property(property="git_url", type="string", example="git://github.com/Jo1oPedro/desafio-reportei-back.git"),
     *              @OA\Property(property="ssh_url", type="string", example="git@github.com:Jo1oPedro/desafio-reportei-back.git"),
     *              @OA\Property(property="clone_url", type="string", example="https://github.com/Jo1oPedro/desafio-reportei-back.git"),
     *              @OA\Property(property="svn_url", type="string", example="https://github.com/Jo1oPedro/desafio-reportei-back"),
     *              @OA\Property(property="homepage", type="string", example=null),
     *              @OA\Property(property="size", type="integer", example=2627),
     *              @OA\Property(property="stargazers_count", type="integer", example=0),
     *              @OA\Property(property="watchers_count", type="integer", example=0),
     *              @OA\Property(property="language", type="string", example="PHP"),
     *              @OA\Property(property="has_issues", type="boolean", example=true),
     *              @OA\Property(property="has_projects", type="boolean", example=true),
     *              @OA\Property(property="has_downloads", type="boolean", example=true),
     *              @OA\Property(property="has_wiki", type="boolean", example=false),
     *              @OA\Property(property="has_pages", type="boolean", example=false),
     *              @OA\Property(property="has_discussions", type="boolean", example=false),
     *              @OA\Property(property="forks_count", type="integer", example=0),
     *              @OA\Property(property="mirror_url", type="string", example=null),
     *              @OA\Property(property="archived", type="boolean", example=false),
     *              @OA\Property(property="disabled", type="boolean", example=false),
     *              @OA\Property(property="open_issues_count", type="integer", example=0),
     *              @OA\Property(property="license", type="string", example=null),
     *              @OA\Property(property="allow_forking", type="boolean", example=true),
     *              @OA\Property(property="is_template", type="boolean", example=false),
     *              @OA\Property(property="web_commit_signoff_required", type="boolean", example=false),
     *              @OA\Property(property="topics", type="array", @OA\Items(type="string")),
     *              @OA\Property(property="visibility", type="string", example="public"),
     *              @OA\Property(property="forks", type="integer", example=0),
     *              @OA\Property(property="open_issues", type="integer", example=0),
     *              @OA\Property(property="watchers", type="integer", example=0),
     *              @OA\Property(property="default_branch", type="string", example="main"),
     *              @OA\Property(
     *                  property="permissions",
     *                  type="object",
     *                  @OA\Property(property="admin", type="boolean", example=true),
     *                  @OA\Property(property="maintain", type="boolean", example=true),
     *                  @OA\Property(property="push", type="boolean", example=true),
     *                  @OA\Property(property="triage", type="boolean", example=true),
     *                  @OA\Property(property="pull", type="boolean", example=true)
     *              ),
     *              @OA\Property(property="network_count", type="integer", example=0),
     *              @OA\Property(property="subscribers_count", type="integer", example=1)
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Not Found.")
     *          )
     *      )
     *  )
     */
    public function show(string $repository_name)
    {
        return Github::getRepository(
            auth()->user()->github_login,
            $repository_name
        );
    }
}
