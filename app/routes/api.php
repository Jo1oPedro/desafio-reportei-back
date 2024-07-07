<?php

use App\Http\Controllers\Api\GithubRepositoryCommitController;
use App\Http\Controllers\Api\GithubRepositoryController;
use App\Http\Controllers\Api\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware("auth:sanctum")->group(function () {
    Route::get("/github/repositories", [GithubRepositoryController::class, "index"]);
    Route::get("/github/repositories/{owner_name}/{repository_name}/{repository_id}", [GithubRepositoryController::class, "show"]);
    Route::get("/github/repository/commits/{owner_name}/{repository_name}/{repository_id}", [GithubRepositoryCommitController::class, "show"]);
});

Route::post('/register', [RegisterController::class, 'register']);
