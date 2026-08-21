<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Mesa;
use App\Models\Party;
use App\Models\TeeMember;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudPersistenciaTest extends TestCase
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

    public function test_store_estudiante_persists(): void
    {
        $this->actingAsAdmin();

        $this->post('/admin/estudiantes', [
            'identificacion' => '9999999',
            'nombre' => 'Juan',
            'apellidos' => 'Perez',
            'seccion' => '11-1',
        ])->assertRedirect();

        $this->assertDatabaseHas('students', [
            'identificacion' => '9999999',
            'nombre' => 'Juan',
        ]);
    }

    public function test_store_mesa_persists(): void
    {
        $this->actingAsAdmin();

        $this->post('/admin/mesas', [
            'numero' => 'Mesa 99',
            'ubicacion' => 'Edificio Nuevo',
        ])->assertRedirect();

        $this->assertDatabaseHas('mesas', [
            'nombre' => 'Mesa 99',
            'ubicacion' => 'Edificio Nuevo',
        ]);
    }

    public function test_store_partido_persists(): void
    {
        $this->actingAsAdmin();

        $this->post('/admin/partidos', [
            'siglas' => 'PXX',
            'nombre' => 'Partido de Prueba',
        ])->assertRedirect();

        $this->assertDatabaseHas('parties', [
            'siglas' => 'PXX',
            'nombre' => 'Partido de Prueba',
        ]);
    }

    public function test_store_tee_member_persists(): void
    {
        $this->actingAsAdmin();

        $student = Student::where('identificacion', '1000001')->first();

        $this->post('/admin/tee', [
            'student_id' => $student->id,
            'puesto' => 'Presidente',
        ])->assertRedirect();

        $this->assertDatabaseHas('tee_members', [
            'student_id' => $student->id,
            'puesto' => 'Presidente',
        ]);
    }

    public function test_store_incidente_persists(): void
    {
        $this->actingAsAdmin();

        $mesa = Mesa::first();
        $user = User::where('name', 'admin')->first();

        $this->post('/admin/incidentes', [
            'mesa_id' => $mesa->id,
            'detalle' => 'Prueba de incidente automatizada',
        ])->assertRedirect();

        $this->assertDatabaseHas('incidentes', [
            'mesa_id' => $mesa->id,
            'user_id' => $user->id,
            'detalle' => 'Prueba de incidente automatizada',
        ]);
    }

    public function test_store_seccion_persists(): void
    {
        $this->actingAsAdmin();

        $mesa = Mesa::first();

        $this->post("/admin/mesas/{$mesa->id}/secciones", [
            'seccion' => 'ZZ',
        ])->assertRedirect();

        $this->assertDatabaseHas('secciones_mesa', [
            'mesa_id' => $mesa->id,
            'seccion' => 'ZZ',
        ]);
    }

    public function test_update_mesa_persists(): void
    {
        $this->actingAsAdmin();

        $mesa = Mesa::first();

        $this->put("/admin/mesas/{$mesa->id}", [
            'numero' => 'Mesa Actualizada',
            'ubicacion' => 'Ubicacion Nueva',
        ])->assertRedirect();

        $this->assertDatabaseHas('mesas', [
            'id' => $mesa->id,
            'nombre' => 'Mesa Actualizada',
            'ubicacion' => 'Ubicacion Nueva',
        ]);
    }

    public function test_update_partido_persists(): void
    {
        $this->actingAsAdmin();

        $partido = Party::first();

        $this->put("/admin/partidos/{$partido->id}", [
            'siglas' => 'PZZ',
            'nombre' => 'Partido Actualizado',
        ])->assertRedirect();

        $this->assertDatabaseHas('parties', [
            'id' => $partido->id,
            'siglas' => 'PZZ',
            'nombre' => 'Partido Actualizado',
        ]);
    }

    public function test_delete_estudiante_removes(): void
    {
        $this->actingAsAdmin();

        $student = Student::where('identificacion', '1000007')->first();
        $id = $student->id;

        $this->delete("/admin/estudiantes/{$id}")->assertRedirect();
        $this->assertDatabaseMissing('students', ['id' => $id]);
    }

    public function test_delete_mesa_removes(): void
    {
        $this->actingAsAdmin();

        $mesa = Mesa::first();
        $id = $mesa->id;

        $this->delete("/admin/mesas/{$id}")->assertRedirect();
        $this->assertDatabaseMissing('mesas', ['id' => $id]);
    }

    public function test_delete_tee_member_removes(): void
    {
        $this->actingAsAdmin();

        $student = Student::where('identificacion', '1000003')->first();
        $this->post('/admin/tee', [
            'student_id' => $student->id,
            'puesto' => 'Secretario',
        ]);

        $tee = TeeMember::where('student_id', $student->id)->first();
        $id = $tee->id;

        $this->delete("/admin/tee/{$id}")->assertRedirect();
        $this->assertDatabaseMissing('tee_members', ['id' => $id]);
    }
}
