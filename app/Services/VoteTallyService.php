<?php

namespace App\Services;

use App\DTOs\VoteTallyResult;
use Illuminate\Support\Collection;

class VoteTallyService
{
    public function tallyVotes(Collection|array $votes): VoteTallyResult
    {
        $votesCollection = collect($votes);

        $resultados =$votesCollection->groupBy('candidato_id')->map->count()->toArray();

        return new VoteTallyResult($resultados);
    }

    public function tallyVotesByMesa(Collection|array $votes, Collection|array $mesas): array
    {
        $votesCollection =collect($votes);

        $resultadosPorMesa = $votesCollection->groupBy('mesa_id')->map(function ($votosEnMesa) {
            return $votosEnMesa->groupBy('candidato_id')->map->count()->toArray();
        });

        return $resultadosPorMesa->toArray();
    }

    public function tallyVotesBySeccion(Collection|array $votes, Collection|array $mesas): array
    {
        $votesCollection = collect($votes);

        $votosPorSeccion = $votesCollection->groupBy('seccion_id')->map(function ($votosEnSeccion) {
            return $votosEnSeccion->groupBy('candidato_id')->map->count()->toArray();
        });

        return $votosPorSeccion->toArray();
    }
}
