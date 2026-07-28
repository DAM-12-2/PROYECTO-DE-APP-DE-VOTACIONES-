<?php

namespace App\DTOs;

class VoteTallyResult
{
    public function __construct(
        public readonly int $totalVotes,
        public readonly int $blancos,
        public readonly int $nulos,
        public readonly int $siCount,
        public readonly int $noCount,
        public readonly array $partyVotes,
    ) {
    }

     public function votosValidos(): int
    {
        return $this->totalVotes - $this->blancos - $this->nulos;
    }

    public function blancoPct(): float
    {
        return $this->totalVotes > 0 ? round(($this->blancos / $this->totalVotes) * 100, 1) : 0;
    }

    public function nuloPct(): float
    {
        return $this->totalVotes > 0 ? round(($this->nulos / $this->totalVotes) * 100, 1) : 0;
    }

    public function siPct(): float
    {
        $validos = $this->votosValidos();
        return $validos > 0 ? round(($this->siCount / $validos) * 100, 1) : 0;
    }

    public function noPct(): float
    {
        $validos = $this->votosValidos();
        return $validos > 0 ? round(($this->noCount / $validos) * 100, 1) : 0;
    }

    public function partyCount(string $partyId): int
    {
        return $this->partyVotes[$partyId] ?? 0;
    }

    public function partyPct(string $partyId): float
    {
        $validos = $this->votosValidos();
        $count = $this->partyCount($partyId);
        return $validos > 0 ? round(($count / $validos) * 100, 1) : 0;
    }

    public function sortedPartyResults(array $parties): array
    {
        $results = [];
        foreach ($parties as $party) {
            $count = $this->partyCount((string) $party->id);
            $results[] = [
                'siglas' => $party->siglas,
                'nombre' => $party->nombre,
                'votos' => $count,
                'porcentaje' => $this->partyPct((string) $party->id),
            ];
        }
        usort($results, fn($a, $b) => $b['votos'] <=> $a['votos']);
        return $results;
    }
}
