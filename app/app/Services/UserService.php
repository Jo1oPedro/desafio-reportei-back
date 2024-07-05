<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Mail\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserService
{
    public function create(UserDTO $user): User
    {
        $user = User::updateOrCreate(
            ['github_id' => $user->getGithubId()],
            [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'avatar_url' => $user->getAvatarUrl(),
                'github_login' => $user->getGithubLogin(),
                "access_token" => $user->getAccessToken(),
            ]
        );

        if($user->wasRecentlyCreated) {
            Mail::to($user)->queue(new UserRegistered($user->name));
        }

        return $user;
    }
}
