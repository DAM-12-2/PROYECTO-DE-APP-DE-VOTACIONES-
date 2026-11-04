<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use App\Models\Vote;
use App\Models\Setting;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DesbloqueoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        Setting::updateOrCreate(['nombre' => 'eleccion_abierta'], ['detalle' => '1']);
    }

    private function votar(): Student
    {
        $this->actingAs(User::where('name', 'jrv')->first());
        $this->postJson('/jrv/api/votar', [
            'identificacion' => '1000001',
            'party_id' => 1,
        ])->assertStatus(201);

        return Student::where('identificacion', '1000001')->first();
    }

    public function test_desbloquear_reinicia_voto_y_elimina_registro(): void
    {
        $estudiante = $this->votar();
        $this->assertSame(1, Vote::count());

        $this->actingAs(User::where('name', 'admin')->first());
        $this->post("/admin/estudiantes/{$estudiante->id}/desbloquear");

        $estudiante->refresh();
        $this->assertFalse((bool) $estudiante->voto);
        $this->assertSame(0, Vote::count());
    }

    public function test_desbloquear_sin_voto_previo(): void
    {
        $this->actingAs(User::where('name', 'admin')->first());
        $estudiante = Student::where('identificacion', '1000003')->first();

        $this->post("/admin/estudiantes/{$estudiante->id}/desbloquear");

        $this->assertFalse((bool) $estudiante->refresh()->voto);
    }
}
