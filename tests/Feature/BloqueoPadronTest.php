<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BloqueoPadronTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->actingAs(User::where('name', 'admin')->first());
    }

    private function abrirEleccion(): void
    {
        Setting::updateOrCreate(['nombre' => 'eleccion_abierta'], ['detalle' => '1']);
    }

    public function test_edicion_bloqueada_con_eleccion_abierta(): void
    {
        $this->abrirEleccion();

        $response = $this->put('/admin/estudiantes/1', [
            'identificacion' => '1000001',
            'nombre' => 'Ana',
            'apellidos' => 'Cambiada',
            'seccion' => '11-1',
        ]);

        $this->assertDatabaseMissing('students', ['apellidos' => 'Cambiada']);
    }

    public function test_importacion_bloqueada_con_eleccion_abierta(): void
    {
        $this->abrirEleccion();

        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('p.csv', "identificacion,nombre,apellidos,seccion\n9000001,Test,Uno,12-9");
        $this->post('/admin/estudiantes/importar', ['csv_file' => $file]);

        $this->assertDatabaseMissing('students', ['identificacion' => '9000001']);
    }

    public function test_edicion_permitida_con_eleccion_cerrada(): void
    {
        $response = $this->put('/admin/estudiantes/1', [
            'identificacion' => '1000001',
            'nombre' => 'Ana',
            'apellidos' => 'Cambiada',
            'seccion' => '11-1',
        ]);

        $this->assertDatabaseHas('students', ['apellidos' => 'Cambiada']);
    }
}
