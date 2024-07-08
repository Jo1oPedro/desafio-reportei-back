<?php

namespace App\Services;

use App\DTO\CommitDTO;
use App\Models\Commit;

class CommitService
{
    public function create(CommitDTO $commitDTO): Commit
    {
        return Commit::firstOrCreate(
            ["commit_id" => $commitDTO->getCommitId()],
            [
                "author_login" => $commitDTO->getAuthorLogin(),
                "author_id" => $commitDTO->getAuthorId(),
                "repository_id" => $commitDTO->getRepositoryId(),
                "commited_at" => $commitDTO->getCommitedAt()
            ]
        );
    }
}
