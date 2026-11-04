<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Urna;
use App\Models\User;
use App\Models\Setting;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KioskoFlujoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        Setting::updateOrCreate(['nombre' => 'eleccion_abierta'], ['detalle' => '1']);
    }

    private function jrv(): User
    {
        return User::where('name', 'jrv')->first();
    }

    public function test_activacion_exitosa_crea_sesion_de_urna(): void
    {
        $response = $this->post('/kiosko/activar', [
            'codigo' => 'U001',
            'usuario' => 'jrv',
            'password' => 'jrv',
        ]);

        $response->assertRedirect('/kiosko');
        $this->assertAuthenticatedAs($this->jrv());
        $this->assertSame(1, Urna::where('codigo', 'U001')->value('estado'));
    }

    public function test_activacion_rechaza_contraseña_incorrecta(): void
    {
        $response = $this->post('/kiosko/activar', [
            'codigo' => 'U001',
            'usuario' => 'jrv',
            'password' => 'incorrecta',
        ]);

        $response->assertSessionHasErrors('usuario');
        $this->assertGuest();
    }

    public function test_activacion_rechaza_urna_de_otra_mesa(): void
    {
        $response = $this->post('/kiosko/activar', [
            'codigo' => 'U002',
            'usuario' => 'jrv',
            'password' => 'jrv',
        ]);

        $response->assertSessionHasErrors('usuario');
        $this->assertGuest();
    }

    public function test_asignacion_de_estudiante_y_estado_del_kiosko(): void
    {
        $this->post('/kiosko/activar', ['codigo' => 'U001', 'usuario' => 'jrv', 'password' => 'jrv']);
        $urna = Urna::where('codigo', 'U001')->first();

        $this->postJson('/jrv/api/asignar', [
            'urna_id' => $urna->id,
            'identificacion' => '1000001',
        ])->assertStatus(200);

        $urna->refresh();
        $this->assertNotNull($urna->id_estudiante);
        $this->assertSame(2, $urna->estado);

        $estado = $this->getJson('/kiosko/estado');
        $estado->assertStatus(200)
            ->assertJsonPath('autenticado', true)
            ->assertJsonPath('estudiante.identificacion', '1000001');
    }

    public function test_voto_libera_la_urna(): void
    {
        $this->post('/kiosko/activar', ['codigo' => 'U001', 'usuario' => 'jrv', 'password' => 'jrv']);
        $urna = Urna::where('codigo', 'U001')->first();
        $estudiante = Student::where('identificacion', '1000001')->first();

        $this->postJson('/jrv/api/asignar', [
            'urna_id' => $urna->id,
            'identificacion' => '1000001',
        ])->assertStatus(200);

        $this->postJson('/jrv/api/votar', [
            'identificacion' => '1000001',
            'party_id' => 1,
        ])->assertStatus(201);

        $urna->refresh();
        $this->assertNull($urna->id_estudiante);
        $this->assertSame(1, $urna->estado);
        $this->assertTrue((bool) $estudiante->refresh()->voto);
    }

    public function test_no_asigna_estudiante_que_ya_voto(): void
    {
        $this->post('/kiosko/activar', ['codigo' => 'U001', 'usuario' => 'jrv', 'password' => 'jrv']);
        $urna = Urna::where('codigo', 'U001')->first();

        $this->postJson('/jrv/api/asignar', [
            'urna_id' => $urna->id,
            'identificacion' => '1000002',
        ])->assertStatus(409);
    }
}
