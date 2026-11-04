<?php

namespace App\Services;

use App\Models\Mesa;
use App\Models\TallySeal;

class TallySealService
{
    private VoteTallyService $tally;

    public function __construct(VoteTallyService $tally)
    {
        $this->tally = $tally;
    }

    public function sealAll(): int
    {
        $selladas = 0;

        foreach (Mesa::orderBy('id')->get() as $mesa) {
            $resultado = $this->tally->tallyMesa($mesa->id);

            TallySeal::updateOrCreate(
                ['mesa_id' => $mesa->id],
                [
                    'hash' => $this->hash($resultado),
                    'total' => $resultado['total'],
                    'blancos' => $resultado['blancos'],
                    'nulos' => $resultado['nulos'],
                    'sealed_at' => now(),
                ]
            );

            $selladas++;
        }

        return $selladas;
    }

    public function verifyAll(): array
    {
        $incidencias = [];

        foreach (TallySeal::with('mesa')->orderBy('mesa_id')->get() as $sello) {
            $resultado = $this->tally->tallyMesa($sello->mesa_id);
            $actual = $this->hash($resultado);

            if (!hash_equals($sello->hash, $actual)) {
                $incidencias[] = [
                    'mesa_id' => $sello->mesa_id,
                    'mesa' => $sello->mesa?->nombre ?? "Mesa {$sello->mesa_id}",
                    'sellado' => $sello->sealed_at,
                    'total_sellado' => $sello->total,
                    'total_actual' => $resultado['total'],
                ];
            }
        }

        return $incidencias;
    }

    public function sinSellar(): array
    {
        $selladas = TallySeal::pluck('mesa_id')->all();

        return Mesa::whereNotIn('id', $selladas)->pluck('nombre', 'id')->all();
    }

    private function hash(array $resultado): string
    {
        $partidos = collect($resultado['partidos'])
            ->map(fn ($p) => [
                'siglas' => (string) ($p['siglas'] ?? ''),
                'votos' => (int) ($p['votos'] ?? 0),
            ])
            ->sortBy('siglas')
            ->values()
            ->all();

        return hash('sha256', json_encode([
            'mesa' => $resultado['mesa_id'],
            'total' => $resultado['total'],
            'blancos' => $resultado['blancos'],
            'nulos' => $resultado['nulos'],
            'partidos' => $partidos,
        ], JSON_UNESCAPED_UNICODE));
    }
}
