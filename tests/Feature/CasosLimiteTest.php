<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CasosLimiteTest extends TestCase
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

    public function test_no_permite_votar_con_eleccion_cerrada(): void
    {
        Setting::updateOrCreate(['nombre' => 'eleccion_abierta'], ['detalle' => '0']);
        $this->actingAs($this->jrv());

        $this->postJson('/jrv/api/votar', [
            'identificacion' => '1000001',
            'party_id' => 1,
        ])->assertStatus(423);
    }

    public function test_no_permite_partido_inexistente(): void
    {
        $this->actingAs($this->jrv());

        $this->postJson('/jrv/api/votar', [
            'identificacion' => '1000001',
            'party_id' => 9999,
        ])->assertStatus(422);
    }

    public function test_no_permite_activar_urna_con_eleccion_cerrada(): void
    {
        Setting::updateOrCreate(['nombre' => 'eleccion_abierta'], ['detalle' => '0']);

        $response = $this->post('/kiosko/activar', [
            'codigo' => 'U001',
            'usuario' => 'jrv',
            'password' => 'jrv',
        ]);

        $response->assertSessionHasErrors('codigo');
        $this->assertGuest();
    }

    public function test_usuario_con_cambio_pendiente_no_activa_urna(): void
    {
        $this->jrv()->update(['must_change_password' => true]);

        $response = $this->post('/kiosko/activar', [
            'codigo' => 'U001',
            'usuario' => 'jrv',
            'password' => 'jrv',
        ]);

        $response->assertSessionHasErrors('usuario');
        $this->assertGuest();
    }

    public function test_login_con_cambio_pendiente_redirige_a_cambiar_password(): void
    {
        $this->jrv()->update(['must_change_password' => true]);

        $this->post('/login', ['username' => 'jrv', 'password' => 'jrv'])
            ->assertRedirect(route('password.change'));
    }

    public function test_cambio_de_password_funciona(): void
    {
        $this->actingAs($this->jrv()->fresh());

        $this->post('/cambiar-password', [
            'current_password' => 'jrv',
            'password' => 'NuevaClave2026',
            'password_confirmation' => 'NuevaClave2026',
        ])->assertRedirect('/jrv');

        $this->assertFalse((bool) $this->jrv()->refresh()->must_change_password);
    }

    public function test_middleware_bloquea_api_si_debe_cambiar_password(): void
    {
        $this->jrv()->update(['must_change_password' => true]);
        $this->actingAs($this->jrv()->fresh());

        $this->postJson('/jrv/api/votar', [
            'identificacion' => '1000001',
            'party_id' => 1,
        ])->assertStatus(409);
    }
}
