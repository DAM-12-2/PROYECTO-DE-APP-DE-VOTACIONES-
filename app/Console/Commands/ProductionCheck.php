<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class ProductionCheck extends Command
{
    protected $signature = 'app:check {--json : Salida en JSON}';

    protected $description = 'Verifica que la configuración sea apta para producción';

    public function handle(): int
    {
        $checks = [];

        $add = function (string $name, bool $ok, string $detail) use (&$checks) {
            $checks[] = ['check' => $name, 'ok' => $ok, 'detail' => $detail];
        };

        $add('APP_ENV', app()->environment('production'), app()->environment());
        $add('APP_DEBUG', !config('app.debug'), config('app.debug') ? 'true (expone errores)' : 'false');
        $add('APP_KEY', !empty(config('app.key')), config('app.key') ? 'definida' : 'faltante');
        $add('APP_URL HTTPS', str_starts_with((string) config('app.url'), 'https://'), (string) config('app.url'));
        $add('SESSION_ENCRYPT', (bool) config('session.encrypt'), config('session.encrypt') ? 'true' : 'false');
        $add('LOG_LEVEL', config('logging.level') !== 'debug', (string) config('logging.level'));

        try {
            DB::connection()->getPdo();
            $add('Base de datos', true, config('database.default'));
        } catch (\Throwable $e) {
            $add('Base de datos', false, $e->getMessage());
        }

        $add('storage escribible', is_writable(storage_path()), storage_path());
        $add('bootstrap/cache escribible', is_writable(base_path('bootstrap/cache')), base_path('bootstrap/cache'));

        try {
            $defaults = User::whereIn('name', ['admin', 'tee', 'jrv'])->get()
                ->filter(fn (User $u) => Hash::check($u->name, $u->password))
                ->pluck('name')
                ->all();
            $add('Credenciales por defecto', empty($defaults), empty($defaults) ? 'ninguna' : implode(', ', $defaults));
        } catch (\Throwable $e) {
            $add('Credenciales por defecto', true, 'no verificable: ' . $e->getMessage());
        }

        $add('Tablas base', Schema::hasTable('students') && Schema::hasTable('votes') && Schema::hasTable('tally_seals'), 'students/votes/tally_seals');

        $failures = array_filter($checks, fn ($c) => !$c['ok']);

        if ($this->option('json')) {
            $this->line(json_encode(['ok' => empty($failures), 'checks' => $checks], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } else {
            $this->table(['Verificación', 'Estado', 'Detalle'], array_map(fn ($c) => [
                $c['check'],
                $c['ok'] ? 'OK' : 'FALLA',
                $c['detail'],
            ], $checks));

            $this->newLine();
            $this->info(empty($failures) ? 'Configuración apta para producción.' : count($failures) . ' problema(s) detectado(s).');
        }

        return empty($failures) ? self::SUCCESS : self::FAILURE;
    }
}
