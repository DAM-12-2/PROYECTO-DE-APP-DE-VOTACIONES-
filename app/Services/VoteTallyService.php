<?php

namespace App\Services;

use App\DTOs\VoteTallyResult;
use App\Models\Party;
use App\Models\Vote;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;

class VoteTallyService
{
    private bool $isConsultaPopular;
    private array $validPartyIds;

    public function __construct()
    {
        $this->isConsultaPopular = $this->detectConsultaPopular();
        $this->validPartyIds = $this->getValidPartyIds();
    }

    private function detectConsultaPopular(): bool
    {
        $tipo = \App\Models\Setting::where('nombre', 'tipo_eleccion')->value('detalle');
        return $tipo === 'consulta_popular';
    }

    private function getValidPartyIds(): array
    {
        if ($this->isConsultaPopular) {
            return ['1', '0'];
        }
        return Party::where('estado', 1)->pluck('id')->map(fn($id) => (string)$id)->toArray();
    }

    public function tallyVotes(Collection|array $votes = null): VoteTallyResult
    {
        if ($votes === null) {
            $votes = Vote::select('encrypted_party', 'id_mesa')->get();
        } elseif ($votes instanceof Collection) {
            // already a collection
        } else {
            $votes = collect($votes);
        }

        $total = $votes->count();
        $blancos = 0;
        $nulos = 0;
        $siCount = 0;
        $noCount = 0;
        $partyVotes = [];

        foreach ($votes as $vote) {
            try {
                $decrypted = Crypt::decryptString($vote->encrypted_party);
            } catch (\Exception $e) {
                $nulos++;
                continue;
            }

            if ($this->isConsultaPopular) {
                if ($decrypted === '1') {
                    $siCount++;
                } elseif ($decrypted === '0') {
                    $noCount++;
                } else {
                    $nulos++;
                }
            } else {
                if (in_array($decrypted, $this->validPartyIds, true)) {
                    $partyVotes[$decrypted] = ($partyVotes[$decrypted] ?? 0) + 1;
                } elseif ($decrypted === '') {
                    $blancos++;
                } else {
                    $nulos++;
                }
            }
        }

        return new VoteTallyResult($total, $blancos, $nulos, $siCount, $noCount, $partyVotes);
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

    public function tallyVotesByMesa(): array
    {
        $votes = Vote::select('encrypted_party', 'id_mesa')->get();
        $mesas = \App\Models\Mesa::with('secciones')->get()->keyBy('id');

        $byMesa = [];

        foreach ($votes as $vote) {
            $mesaId = $vote->id_mesa;
            if (!$mesaId || !isset($mesas[$mesaId])) {
                continue;
            }

            if (!isset($byMesa[$mesaId])) {
                $byMesa[$mesaId] = [
                    'mesa' => $mesas[$mesaId],
                    'votes' => [],
                ];
            }
            $byMesa[$mesaId]['votes'][] = $vote;
        }

        $results = [];
        foreach ($byMesa as $mesaId => $data) {
            $result = $this->tallyVotesCollection($data['votes']);
            $results[$mesaId] = [
                'mesa_id' => $mesaId,
                'mesa_nombre' => $data['mesa']->nombre,
                'mesa_numero' => $data['mesa']->numero,
                'total' => $result->totalVotes,
                'blancos' => $result->blancos,
                'nulos' => $result->nulos,
                'votos_validos' => $result->votosValidos(),
                'partidos' => $this->formatPartyResults($result),
            ];
        }

        ksort($results);
        return array_values($results);
    }

    public function tallyVotesBySeccion(): array
    {
        $votes = Vote::select('encrypted_party', 'id_mesa')->get();
        $mesas = \App\Models\Mesa::with('secciones')->get()->keyBy('id');

        $bySeccion = [];

        foreach ($votes as $vote) {
            $mesaId = $vote->id_mesa;
            if (!$mesaId || !isset($mesas[$mesaId])) {
                continue;
            }

            $mesa = $mesas[$mesaId];
            foreach ($mesa->secciones as $seccion) {
                $seccionKey = $seccion->seccion;
                if (!isset($bySeccion[$seccionKey])) {
                    $bySeccion[$seccionKey] = [
                        'seccion' => $seccionKey,
                        'mesa_ids' => [],
                        'votes' => [],
                    ];
                }
                if (!in_array($mesaId, $bySeccion[$seccionKey]['mesa_ids'], true)) {
                    $bySeccion[$seccionKey]['mesa_ids'][] = $mesaId;
                }
                $bySeccion[$seccionKey]['votes'][] = $vote;
            }
        }

        $results = [];
        foreach ($bySeccion as $seccionKey => $data) {
            $result = $this->tallyVotesCollection($data['votes']);
            $results[$seccionKey] = [
                'seccion' => $seccionKey,
                'mesa_ids' => $data['mesa_ids'],
                'total' => $result->totalVotes,
                'blancos' => $result->blancos,
                'nulos' => $result->nulos,
                'votos_validos' => $result->votosValidos(),
                'partidos' => $this->formatPartyResults($result),
            ];
        }

        ksort($results);
        return array_values($results);
    }

    private function tallyVotesCollection($votes): VoteTallyResult
    {
        $total = count($votes);
        $blancos = 0;
        $nulos = 0;
        $siCount = 0;
        $noCount = 0;
        $partyVotes = [];

        foreach ($votes as $vote) {
            try {
                $decrypted = Crypt::decryptString($vote->encrypted_party);
            } catch (\Exception $e) {
                $nulos++;
                continue;
            }

            if ($this->isConsultaPopular) {
                if ($decrypted === '1') {
                    $siCount++;
                } elseif ($decrypted === '0') {
                    $noCount++;
                } else {
                    $nulos++;
                }
            } else {
                if (in_array($decrypted, $this->validPartyIds, true)) {
                    $partyVotes[$decrypted] = ($partyVotes[$decrypted] ?? 0) + 1;
                } elseif ($decrypted === '') {
                    $blancos++;
                } else {
                    $nulos++;
                }
            }
        }

        return new VoteTallyResult($total, $blancos, $nulos, $siCount, $noCount, $partyVotes);
    }

    public function formatPartyResults(VoteTallyResult $result): array
    {
        if ($this->isConsultaPopular) {
            $validos = $result->votosValidos();
            return [
                ['siglas' => 'SÍ', 'nombre' => 'Sí', 'votos' => $result->siCount, 'porcentaje' => $validos > 0 ? round(($result->siCount / $validos) * 100, 1) : 0],
                ['siglas' => 'NO', 'nombre' => 'No', 'votos' => $result->noCount, 'porcentaje' => $validos > 0 ? round(($result->noCount / $validos) * 100, 1) : 0],
            ];
        }

        $parties = Party::where('estado', 1)->get()->all();
        return $result->sortedPartyResults($parties);
    }
}