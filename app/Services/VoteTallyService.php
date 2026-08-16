<?php

namespace App\Services;

use App\DTOs\VoteTallyResult;
use App\Models\Party;
use App\Models\Vote;
use Illuminate\Support\Collection;

class VoteTallyService
{
    public function tallyVotes(Collection|array $votes): VoteTallyResult
    {
        $votes = $votes instanceof Collection ? $votes : collect($votes);

        $partyVotes = [];
        $blancos = 0;
        $nulos = 0;

        foreach ($votes as $vote) {
            $valor = $vote->decrypted_party;

            if ($valor === null) {
                $nulos++;
                continue;
            }

            if ($valor === 'BLANCO') {
                $blancos++;
                continue;
            }

            $partyVotes[$valor] = ($partyVotes[$valor] ?? 0) + 1;
        }

        return new VoteTallyResult(
            totalVotes: $votes->count(),
            blancos: $blancos,
            nulos: $nulos,
            siCount: 0,
            noCount: 0,
            partyVotes: $partyVotes,
        );
    }

    public function obtenerResultados(): array
    {
        $tally = $this->tallyVotes(Vote::all());

        return [
            'totalVotes' => $tally->totalVotes,
            'blancos' => $tally->blancos,
            'nulos' => $tally->nulos,
            'partidos' => $tally->sortedPartyResults(Party::all()->all()),
        ];
    }

    public function verificarGanador(): ?array
    {
        $tally = $this->tallyVotes(Vote::all());
        $results = $tally->sortedPartyResults(Party::all()->all());

        if (empty($results) || $results[0]['votos'] === 0) {
            return null;
        }

        return $results[0];
    }

    public function tallyVotesByMesa(Collection|array $votes, Collection|array $mesas): array
    {
        $resultadosPorMesa = [];
        foreach ($mesas as $mesa) {
            $deMesa = collect($votes)->filter(fn($v) => $v->id_mesa === $mesa->id);
            $resultadosPorMesa[$mesa->nombre] = $this->tallyVotes($deMesa);
        }
        return $resultadosPorMesa;
    }

    public function tallyVotesBySeccion(Collection|array $votes, Collection|array $mesas): array
    {
        $resultadosPorSeccion = [];
        foreach ($mesas as $mesa) {
            $deMesa = collect($votes)->filter(fn($v) => $v->id_mesa === $mesa->id);
            $resultadosPorSeccion[$mesa->nombre] = $this->tallyVotes($deMesa);
        }
        return $resultadosPorSeccion;
    }
}
