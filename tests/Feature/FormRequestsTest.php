<?php

namespace Tests\Feature;

use App\Http\Requests\StoreIncidenteRequest;
use App\Http\Requests\StoreMesaRequest;
use App\Http\Requests\StoreTeeRequest;
use App\Http\Requests\UpdateStudentRequest;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormRequestsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_store_mesa_request_rejects_empty(): void
    {
        $request = new StoreMesaRequest();
        $validator = validator([], $request->rules());
        $this->assertTrue($validator->fails());
    }

    public function test_store_mesa_request_accepts_numero(): void
    {
        $request = new StoreMesaRequest();
        $validator = validator([
            'numero' => 'Mesa 1',
            'ubicacion' => 'Edificio A',
        ], $request->rules());
        $this->assertTrue($validator->passes());
    }

    public function test_store_incidente_request_rejects_empty(): void
    {
        $request = new StoreIncidenteRequest();
        $validator = validator([], $request->rules());
        $this->assertTrue($validator->fails());
    }

    public function test_store_tee_request_rejects_empty(): void
    {
        $request = new StoreTeeRequest();
        $validator = validator([], $request->rules());
        $this->assertTrue($validator->fails());
    }

    public function test_update_student_request_accepts_valid(): void
    {
        $request = new UpdateStudentRequest();
        $validator = validator([
            'nombre' => 'Nuevo Nombre',
            'apellidos' => 'Nuevo Apellido',
            'seccion' => '11-1',
        ], $request->rules());
        $this->assertTrue($validator->passes());
    }
}
