<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\TallySeal;
use App\Models\User;
use App\Models\Vote;
use App\Services\TallySealService;
use App\Services\VoteTallyService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class IntegridadConteoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    private function voto(int $mesa = 1, int $partido = 1): void
    {
        Vote::create([
            'encrypted_party' => Crypt::encryptString((string) $partido),
            'id_mesa' => $mesa,
        ]);
    }

    public function test_sellado_y_verificacion_sin_incidencias(): void
    {
        $this->voto(1, 1);
        $this->voto(1, 2);

        $servicio = app(TallySealService::class);
        $this->assertSame(3, $servicio->sealAll());
        $this->assertSame([], $servicio->verifyAll());
    }

    public function test_detecta_voto_alterado_tras_el_sello(): void
    {
        $this->voto(1, 1);

        $servicio = app(TallySealService::class);
        $servicio->sealAll();

        $this->voto(1, 2);

        $incidencias = $servicio->verifyAll();
        $this->assertCount(1, $incidencias);
        $this->assertSame(1, $incidencias[0]['mesa_id']);
        $this->assertSame(1, $incidencias[0]['total_sellado']);
        $this->assertSame(2, $incidencias[0]['total_actual']);
    }

    public function test_cerrar_eleccion_sella_el_conteo(): void
    {
        Setting::updateOrCreate(['nombre' => 'eleccion_abierta'], ['detalle' => '1']);
        $this->actingAs(User::where('name', 'admin')->first());

        $this->post('/admin/eleccion/toggle');

        $this->assertSame('0', Setting::where('nombre', 'eleccion_abierta')->value('detalle'));
        $this->assertSame(3, TallySeal::count());
    }

    public function test_comando_detecta_alteracion(): void
    {
        $this->voto(1, 1);
        app(TallySealService::class)->sealAll();
        $this->voto(1, 2);

        $this->artisan('tally:verify')->assertExitCode(1);
    }

    public function test_vista_de_integridad_renderiza(): void
    {
        $this->actingAs(User::where('name', 'admin')->first());

        $this->get('/admin/integridad')
            ->assertStatus(200)
            ->assertSee('Integridad del conteo');
    }

    public function test_vista_de_resultados_renderiza(): void
    {
        $this->actingAs(User::where('name', 'admin')->first());

        $this->get('/admin/resultados')
            ->assertStatus(200)
            ->assertSee('Resultados')
            ->assertSee('Votos emitidos');
    }
}
