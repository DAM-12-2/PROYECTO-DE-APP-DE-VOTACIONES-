<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TribunalNavegacionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->actingAs(User::where('name', 'tee')->first());
    }

    public function test_tribunal_renderiza_navegacion_real(): void
    {
        $this->get('/tribunal')
            ->assertStatus(200)
            ->assertSee('Integridad')
            ->assertSee('/admin/estudiantes', false)
            ->assertSee('/admin/resultados', false)
            ->assertDontSee('data-target', false);
    }

    public function test_tee_puede_abrir_padron(): void
    {
        $this->get('/admin/estudiantes')
            ->assertStatus(200)
            ->assertSee('Agregar estudiante');
    }
}
