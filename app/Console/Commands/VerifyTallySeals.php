<?php

namespace App\Console\Commands;

use App\Services\TallySealService;
use Illuminate\Console\Command;

class VerifyTallySeals extends Command
{
    protected $signature = 'tally:verify {--seal : Sella el conteo actual antes de verificar}';

    protected $description = 'Verifica que el conteo por mesa coincida con el sello SHA-256 guardado';

    public function handle(TallySealService $sealService): int
    {
        if ($this->option('seal')) {
            $selladas = $sealService->sealAll();
            $this->info("Selladas {$selladas} mesas.");
        }

        $incidencias = $sealService->verifyAll();

        if (empty($incidencias)) {
            $this->info('Conteo íntegro: todas las mesas coinciden con su sello.');
            return self::SUCCESS;
        }

        $this->error(count($incidencias) . ' mesa(s) con diferencias respecto al sello:');
        $this->table(
            ['Mesa', 'Sellado', 'Total sellado', 'Total actual'],
            array_map(fn ($i) => [
                $i['mesa'],
                $i['sellado']?->format('Y-m-d H:i'),
                $i['total_sellado'],
                $i['total_actual'],
            ], $incidencias)
        );

        return self::FAILURE;
    }
}
