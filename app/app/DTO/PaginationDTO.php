<?php

namespace App\DTO;

class PaginationDTO
{
    public function __construct(
        private readonly int $page = 1,
        private readonly int $per_page = 10
    ) {}

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->per_page;
    }
}

