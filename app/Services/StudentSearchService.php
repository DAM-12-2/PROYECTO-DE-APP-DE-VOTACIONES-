<?php

namespace App\Services;

use Illuminate\Support\Collection;

class StudentSearchService
{
    public function search(string $query, ?int $mesaId = null, bool $onlyAvailable = true): Collection
    {
    }

    public function getAvailableStudents(): Collection
    {
    }
}
