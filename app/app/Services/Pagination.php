<?php

namespace App\Services;

class Pagination
{
    public function __construct(
        private int $page = 1,
        private int $per_page = 10
    ){}

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->per_page;
    }


}
