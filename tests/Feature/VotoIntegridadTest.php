<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use App\Models\Vote;
use App\Models\Setting;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VotoIntegridadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        Setting::updateOrCreate(['nombre' => 'eleccion_abierta'], ['detalle' => '1']);
        $this->actingAs(User::where('name', 'jrv')->first());
    }

    public function test_voto_registra_estudiante_y_marca_padrón(): void
    {
        $response = $this->postJson('/jrv/api/votar', [
            'identificacion' => '1000001',
            'party_id' => 1,
        ]);

        $response->assertStatus(201);

        $estudiante = Student::where('identificacion', '1000001')->first();
        $this->assertTrue((bool) $estudiante->voto);

        $voto = Vote::first();
        $this->assertSame($estudiante->id, $voto->student_id);
        $this->assertSame(1, $voto->id_mesa);
    }

    public function test_doble_voto_es_rechazado(): void
    {
        $this->postJson('/jrv/api/votar', [
            'identificacion' => '1000001',
            'party_id' => 1,
        ])->assertStatus(201);

        $this->postJson('/jrv/api/votar', [
            'identificacion' => '1000001',
            'party_id' => 2,
        ])->assertStatus(409);

        $this->assertSame(1, Vote::count());
    }

    public function test_voto_con_estudiante_inexistente(): void
    {
        $this->postJson('/jrv/api/votar', [
            'identificacion' => '9999999',
            'party_id' => 1,
        ])->assertStatus(404);
    }
}
