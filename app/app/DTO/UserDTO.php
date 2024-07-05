<?php

namespace App\DTO;

class UserDTO
{
    public function __construct(
        private readonly string $name,
        private readonly string $email,
        private readonly string $avatar_url,
        private readonly string $github_login,
        private readonly string $github_id,
        private readonly string $access_token
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    public function getAvatarUrl(): string
    {
        return $this->avatar_url;
    }

    public function getGithubLogin(): string
    {
        return $this->github_login;
    }

    public function getGithubId(): string
    {
        return $this->github_id;
    }

    public function getAccessToken(): string
    {
        return $this->access_token;
    }
}
