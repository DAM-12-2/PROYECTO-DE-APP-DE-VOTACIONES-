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

        $blancos = 0;
        $nulos = 0;
        $siCount = 0;
        $noCount = 0;
        $partyVotes = [];

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

            if ($valor === 'SI') {
                $siCount++;
                continue;
            }

            if ($valor === 'NO') {
                $noCount++;
                continue;
            }

            $partyVotes[$valor] = ($partyVotes[$valor] ?? 0) + 1;
        }

        return new VoteTallyResult(
            totalVotes: $votes->count(),
            blancos: $blancos,
            nulos: $nulos,
            siCount: $siCount,
            noCount: $noCount,
            partyVotes: $partyVotes,
        );
    }

    public function tallyVotesByMesa(Collection|array $votes, Collection|array $mesas): array
    {
        $result = [];
        foreach ($mesas as $mesa) {
            $deMesa = collect($votes)->filter(fn($v) => $v->id_mesa === $mesa->id);
            $result[] = [
                'mesa' => $mesa->nombre,
                'total' => $deMesa->count(),
                'resultados' => $this->tallyVotes($deMesa)->sortedPartyResults(Party::all()),
            ];
        }
        return $result;
    }

    public function tallyVotesBySeccion(Collection|array $votes, Collection|array $mesas): array
    {
        $result = [];
        foreach ($mesas as $mesa) {
            $deMesa = collect($votes)->filter(fn($v) => $v->id_mesa === $mesa->id);
            $result[$mesa->nombre] = [
                'total' => $deMesa->count(),
                'resultados' => $this->tallyVotes($deMesa)->sortedPartyResults(Party::all()),
            ];
        }
        return $result;
    }
}
