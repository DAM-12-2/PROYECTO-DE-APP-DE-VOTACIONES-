<?php

namespace App\Services;

use App\DTOs\VoteTallyResult;
use App\Models\Party;
use App\Models\Vote;

class ElectionService
{
    public function __construct(
        private VoteTallyService $voteTallyService,
    ) {
    }

    public function tallyAll(): VoteTallyResult
    {
        return $this->voteTallyService->tallyVotes(Vote::all());
    }

    public function resultadosCompletos(): array
    {
        $tally = $this->tallyAll();
        return [
            'totalVotos' => $tally->totalVotes,
            'blancos' => $tally->blancos,
            'nulos' => $tally->nulos,
            'partidos' => $tally->sortedPartyResults(Party::all()->all()),
        ];
    }

    public function verificarGanador(): array
    {
        $tally = $this->tallyAll();
        $results = $tally->sortedPartyResults(Party::all()->all());

        if (count($results) === 0 || $results[0]['votos'] === 0) {
            return ['ganador' => null, 'message' => 'Aún no hay votos registrados'];
        }

        if (isset($results[1]) && $results[0]['votos'] === $results[1]['votos']) {
            return ['ganador' => null, 'message' => 'Empate entre los primeros lugares'];
        }

        return [
            'ganador' => $results[0],
            'message' => 'Ganador definido',
        ];
    }
}
