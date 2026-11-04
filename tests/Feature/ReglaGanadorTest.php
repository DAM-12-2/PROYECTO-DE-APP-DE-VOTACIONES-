<?php

namespace Tests\Feature;

use App\Models\Party;
use App\Models\Setting;
use App\Models\Vote;
use App\Services\VoteTallyService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class ReglaGanadorTest extends TestCase
{
    use RefreshDatabase;

    private VoteTallyService $tally;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->tally = app(VoteTallyService::class);
    }

    private function votos(int $partyId, int $cantidad): void
    {
        $cifrado = Crypt::encryptString((string) $partyId);
        for ($i = 0; $i < $cantidad; $i++) {
            Vote::create(['encrypted_party' => $cifrado, 'id_mesa' => 1]);
        }
    }

    private function set(string $nombre, string $detalle): void
    {
        Setting::updateOrCreate(['nombre' => $nombre], ['detalle' => $detalle]);
    }

    public function test_mayoria_simple_por_defecto(): void
    {
        $this->votos(1, 3);
        $this->votos(2, 2);

        $ganador = $this->tally->verificarGanador();
        $this->assertNotNull($ganador);
        $this->assertSame(Party::find(1)->siglas, $ganador['siglas']);
    }

    public function test_umbral_porcentaje_minimo_no_alcanzado(): void
    {
        $this->votos(1, 2);
        $this->votos(2, 2); // 50 % < 60 %

        $this->set('requisito_ganar', 'porcentaje_minimo');
        $this->set('porc_minimo', '60');

        $this->assertNull($this->tally->verificarGanador());
    }

    public function test_umbral_porcentaje_minimo_alcanzado(): void
    {
        $this->votos(1, 3);
        $this->votos(2, 1);

        $this->set('requisito_ganar', 'porcentaje_minimo');
        $this->set('porc_minimo', '60');

        $this->assertNotNull($this->tally->verificarGanador());
    }

    public function test_base_empadronados_y_sumar_n(): void
    {
        $this->votos(1, 2);
        $this->votos(2, 1);

        $this->set('requisito_ganar', 'porcentaje_minimo');
        $this->set('porc_minimo', '50');
        $this->set('base_porcentaje', 'empadronados'); // 8 empadronados → umbral 4 votos

        $this->assertNull($this->tally->verificarGanador());

        $this->set('sumar_n', '-8'); // base 0 → sin umbral válido
        $this->assertNull($this->tally->verificarGanador());
    }
}
