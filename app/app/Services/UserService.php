<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Mail\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserService
{
    public function create(UserDTO $user)
    {
        $user = User::updateOrCreate(
            ['email' => $user->email],
            [
                'name' => $user->name,
                'user_id' => $user->user_id,
                'avatar_url' => $user->avatar_url,
                'html_url' => $user->html_url,
                "access_token" => $user->access_token,
            ]
        );
        $token = $user->createToken(env('SECRET'))->plainTextToken;

        if($user->wasRecentlyCreated) {
            Mail::to($user)->queue(new UserRegistered($user->name));
        }

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }
}
