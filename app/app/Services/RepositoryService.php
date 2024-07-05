<?php

namespace App\Services;

use App\DTO\RepositoryDTO;
use App\Models\Repository;

class RepositoryService
{
    public function create(RepositoryDTO $repositoryDTO): Repository
    {
        return Repository::firstOrCreate(
            ["repository_id" => $repositoryDTO->getRepositoryId()],
            [
                "name" => $repositoryDTO->getName(),
                "user_id" => $repositoryDTO->getOwnerId()
            ]
        );
    }
}
