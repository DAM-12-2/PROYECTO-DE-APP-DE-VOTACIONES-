<?php

namespace App\Services;

use App\DTOs\VoteTallyResult;
use Illuminate\Support\Collection;

class VoteTallyService
{
    public function tallyVotes(Collection|array $votes): VoteTallyResult
    {
    }

    public function tallyVotesByMesa(Collection|array $votes, Collection|array $mesas): array
    {
    }

    public function tallyVotesBySeccion(Collection|array $votes, Collection|array $mesas): array
    {
    }
}
