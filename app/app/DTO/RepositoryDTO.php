<?php

namespace App\DTO;

class RepositoryDTO
{
    public function __construct(
        private readonly string $name,
        private readonly string $owner_name,
        private readonly int $owner_id,
        private readonly int $repository_id
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getOwnerId(): string
    {
        return $this->owner_id;
    }

    public function getOwnerName(): string
    {
        return $this->owner_name;
    }

    public function getRepositoryId(): string
    {
        return $this->repository_id;
    }
}
