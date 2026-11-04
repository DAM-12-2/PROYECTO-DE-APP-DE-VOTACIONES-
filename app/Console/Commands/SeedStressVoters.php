<?php

namespace App\Console\Commands;

use App\Models\Student;
use Illuminate\Console\Command;

class SeedStressVoters extends Command
{
    protected $signature = 'stress:seed {count=300 : Cantidad de estudiantes} {--prefix=STRESS : Prefijo de identificación} {--open : Abre la elección y reinicia votos}';

    protected $description = 'Siembra estudiantes de prueba para pruebas de carga (no usar en producción)';

    public function handle(): int
    {
        $count = (int) $this->argument('count');
        $prefix = $this->option('prefix');
        $now = now();

        $rows = [];
        for ($i = 1; $i <= $count; $i++) {
            $rows[] = [
                'identificacion' => sprintf('%s-%04d', $prefix, $i),
                'nombre' => 'Carga',
                'apellidos' => sprintf('Prueba %04d', $i),
                'seccion' => '11-1',
                'voto' => false,
                'estado' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            Student::insert($chunk);
        }

        if ($this->option('open')) {
            \App\Models\Vote::truncate();
            Student::query()->update(['voto' => false]);
            \App\Models\Setting::updateOrCreate(['nombre' => 'eleccion_abierta'], ['detalle' => '1']);
            $this->info('Elección abierta y votos reiniciados.');
        }

        $this->info("Sembrados {$count} estudiantes de prueba con prefijo {$prefix}-.");

        return self::SUCCESS;
    }
}
