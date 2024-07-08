<?php

namespace App\DTO;

use Carbon\Carbon;

class CommitDTO
{
    public function __construct(
        private string $commit_id,
        private string $author_login,
        private string $author_id,
        private string $branch_id,
        private string $commited_at
    ) {}

    public function getCommitId(): string
    {
        return $this->commit_id;
    }

    public function getAuthorLogin(): string
    {
        return $this->author_login;
    }

    public function getAuthorId(): string
    {
        return $this->author_id;
    }

    public function getBranchId(): string
    {
        return $this->branch_id;
    }

    public function getCommitedAt(): string
    {
        return $this->commited_at;
    }
}
