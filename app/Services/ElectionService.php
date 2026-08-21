<?php

namespace App\Services;

use App\DTOs\VoteTallyResult;
use App\Models\Party;
use App\Models\Setting;
use App\Models\Vote;
use App\Services\VoteTallyService;

class ElectionService
{
    public function __construct(
        private VoteTallyService $voteTallyService,
    ) {
    }

    public function isConsultaPopular(): bool
    {
        return Setting::where('nombre', 'tipo_eleccion')->value('detalle') === 'consulta_popular';
    }

    public function isElectionOpen(): bool
    {
        return Setting::where('nombre', 'eleccion_abierta')->value('detalle') === '1';
    }

    public function isPadronBloqueado(): bool
    {
        return Setting::where('nombre', 'padron_bloqueado')->value('detalle') === '1';
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

    public function getWinnerThreshold(): array
    {
        return ['type' => 'mayoria_absoluta', 'value' => 50];
    }

    public function toggle(): array
    {
        $setting = Setting::firstOrCreate(['nombre' => 'eleccion_abierta'], ['detalle' => '0']);
        $setting->detalle = $setting->detalle === '1' ? '0' : '1';
        $setting->save();

        return ['eleccion_abierta' => $setting->detalle === '1'];
    }
}