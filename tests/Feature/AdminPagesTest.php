<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Party;
use App\Models\Mesa;
use App\Models\Urna;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagesTest extends TestCase
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

    public function test_dashboard(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin');
        $response->assertStatus(200);
    }

    public function test_students_index(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin/estudiantes');
        $response->assertStatus(200);
    }

    public function test_parties_index(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin/partidos');
        $response->assertStatus(200);
    }

    public function test_urnas_index(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin/urnas');
        $response->assertStatus(200);
    }

    public function test_mesas_index(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin/mesas');
        $response->assertStatus(200);
    }

    public function test_candidatos_index(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin/candidatos');
        $response->assertStatus(200);
    }

    public function test_incidentes_index(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin/incidentes');
        $response->assertStatus(200);
    }

    public function test_tee_index(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin/tee');
        $response->assertStatus(200);
    }

    public function test_reportes_index(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin/reportes');
        $response->assertStatus(200);
    }

    public function test_configuracion_index(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin/configuracion');
        $response->assertStatus(200);
    }

    public function test_edit_estudiante_page(): void
    {
        $this->actingAsAdmin();
        $student = Student::first();
        $response = $this->get("/admin/estudiantes/{$student->id}/edit");
        $response->assertStatus(200);
    }

    public function test_edit_partido_page(): void
    {
        $this->actingAsAdmin();
        $party = Party::first();
        $response = $this->get("/admin/partidos/{$party->id}/edit");
        $response->assertStatus(200);
    }

    public function test_edit_urna_page(): void
    {
        $this->actingAsAdmin();
        $urna = Urna::first();
        $response = $this->get("/admin/urnas/{$urna->id}/edit");
        $response->assertStatus(200);
    }

    public function test_edit_mesa_page(): void
    {
        $this->actingAsAdmin();
        $mesa = Mesa::first();
        $response = $this->get("/admin/mesas/{$mesa->id}/edit");
        $response->assertStatus(200);
    }

    public function test_edit_usuario_page(): void
    {
        $this->actingAsAdmin();
        $userId = User::first()->id;
        $response = $this->get("/admin/usuarios/{$userId}/edit");
        $response->assertStatus(200);
    }
}
