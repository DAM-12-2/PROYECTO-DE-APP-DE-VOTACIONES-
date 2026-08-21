<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Setting;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    private function actingAsAdmin(): void
    {
        $admin = User::where('name', 'admin')->first();
        $this->actingAs($admin);
    }

    private function ensureElectionClosed(): void
    {
        Setting::updateOrCreate(
            ['nombre' => 'eleccion_abierta'],
            ['nombre' => 'eleccion_abierta', 'detalle' => '0']
        );
    }

    public function test_padron_report(): void
    {
        $this->actingAsAdmin();
        $this->ensureElectionClosed();

        $response = $this->get('/admin/reportes/padron');
        $response->assertStatus(200);
    }

    public function test_acta_apertura_report(): void
    {
        $this->actingAsAdmin();
        $this->ensureElectionClosed();

        $response = $this->get('/admin/reportes/acta-apertura');
        $response->assertStatus(200);
    }

    public function test_incidentes_report(): void
    {
        $this->actingAsAdmin();
        $this->ensureElectionClosed();

        $response = $this->get('/admin/reportes/incidentes');
        $response->assertStatus(200);
    }

    public function test_instrucciones_report(): void
    {
        $this->actingAsAdmin();
        $this->ensureElectionClosed();

        $response = $this->get('/admin/reportes/instrucciones');
        $response->assertStatus(200);
    }

    public function test_acta_cierre_report(): void
    {
        $this->actingAsAdmin();
        $this->ensureElectionClosed();

        $response = $this->get('/admin/reportes/acta-cierre');
        $response->assertStatus(200);
    }

    public function test_acta_resultados_report(): void
    {
        $this->actingAsAdmin();
        $this->ensureElectionClosed();

        $response = $this->get('/admin/reportes/acta-resultados');
        $response->assertStatus(200);
    }
}
