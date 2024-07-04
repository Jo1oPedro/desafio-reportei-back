<?php

namespace App\DTO;

class UserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $avatar_url,
        public string $html_url,
        public string $user_id,
        public string $access_token
    ) {}
}
