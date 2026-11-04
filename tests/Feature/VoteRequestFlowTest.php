<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\Student;
use App\Models\Urna;
use App\Models\User;
use App\Models\VoteRequest;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteRequestFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        Setting::updateOrCreate(['nombre' => 'eleccion_abierta'], ['detalle' => '1']);
        $this->actingAs(User::where('name', 'jrv')->first());
    }

    public function test_registro_crea_solicitud_pendiente(): void
    {
        $response = $this->postJson('/registro/solicitud', ['identificacion' => '1000001']);

        $response->assertStatus(201)
            ->assertJsonPath('data.estado', 'pendiente')
            ->assertJsonPath('data.estudiante.identificacion', '1000001');

        $this->assertDatabaseHas('vote_requests', [
            'student_id' => Student::where('identificacion', '1000001')->value('id'),
            'estado' => 'pendiente',
            'mesa_id' => 1,
        ]);
    }

    public function test_registro_es_idempotente(): void
    {
        $this->postJson('/registro/solicitud', ['identificacion' => '1000001'])->assertStatus(201);
        $this->postJson('/registro/solicitud', ['identificacion' => '1000001'])->assertStatus(200);

        $this->assertSame(1, VoteRequest::count());
    }

    public function test_no_permite_estudiante_que_ya_voto(): void
    {
        $this->postJson('/registro/solicitud', ['identificacion' => '1000002'])
            ->assertStatus(409);
    }

    public function test_jrv_ve_la_cola_de_su_mesa(): void
    {
        $this->postJson('/registro/solicitud', ['identificacion' => '1000001']);

        $this->getJson('/jrv/api/cola')
            ->assertStatus(200)
            ->assertJsonPath('data.0.estudiante.identificacion', '1000001');
    }

    public function test_aprobar_asigna_urna(): void
    {
        $id = $this->postJson('/registro/solicitud', ['identificacion' => '1000001'])->json('data.id');

        $this->postJson("/jrv/api/solicitud/{$id}/aprobar", ['urna_id' => 1])
            ->assertStatus(200)
            ->assertJsonPath('data.estado', 'aprobada')
            ->assertJsonPath('data.urna.codigo', 'U001');

        $urna = Urna::where('codigo', 'U001')->first();
        $this->assertNotNull($urna->id_estudiante);
        $this->assertSame(2, $urna->estado);
    }

    public function test_no_aprueba_si_la_urna_esta_ocupada(): void
    {
        $primera = $this->postJson('/registro/solicitud', ['identificacion' => '1000001'])->json('data.id');
        $segunda = $this->postJson('/registro/solicitud', ['identificacion' => '1000003'])->json('data.id');

        $this->postJson("/jrv/api/solicitud/{$primera}/aprobar", ['urna_id' => 1])->assertStatus(200);
        $this->postJson("/jrv/api/solicitud/{$segunda}/aprobar", ['urna_id' => 1])->assertStatus(409);
    }

    public function test_votar_cierra_la_solicitud_y_libera_la_urna(): void
    {
        $id = $this->postJson('/registro/solicitud', ['identificacion' => '1000001'])->json('data.id');
        $this->postJson("/jrv/api/solicitud/{$id}/aprobar", ['urna_id' => 1])->assertStatus(200);

        $this->postJson('/jrv/api/votar', [
            'identificacion' => '1000001',
            'party_id' => 1,
        ])->assertStatus(201);

        $solicitud = VoteRequest::find($id);
        $this->assertSame('votada', $solicitud->estado);
        $this->assertNotNull($solicitud->voted_at);
        $this->assertNull(Urna::find(1)->id_estudiante);
    }

    public function test_rechazar_elimina_la_solicitud(): void
    {
        $id = $this->postJson('/registro/solicitud', ['identificacion' => '1000001'])->json('data.id');

        $this->postJson("/jrv/api/solicitud/{$id}/rechazar")->assertStatus(200);

        $this->assertDatabaseMissing('vote_requests', ['id' => $id]);
    }

    public function test_pantalla_de_registro_renderiza(): void
    {
        $this->get('/registro')
            ->assertStatus(200)
            ->assertSee('Registro de votante');
    }
}
