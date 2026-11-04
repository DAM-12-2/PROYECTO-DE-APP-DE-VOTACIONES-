<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentsPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->actingAs(User::where('name', 'admin')->first());
    }

    public function test_lista_estudiantes_del_padron(): void
    {
        $this->get('/admin/estudiantes')
            ->assertStatus(200)
            ->assertSee('1000001')
            ->assertSee('Agregar estudiante')
            ->assertSee('Importar padrón');
    }

    public function test_busqueda_filtra_por_identificacion(): void
    {
        $this->get('/admin/estudiantes?q=1000003')
            ->assertStatus(200)
            ->assertSee('1000003')
            ->assertDontSee('1000001');
    }

    public function test_alta_de_estudiante(): void
    {
        $this->post('/admin/estudiantes', [
            'identificacion' => '2000001',
            'nombre' => 'Nuevo',
            'apellidos' => 'Estudiante',
            'seccion' => '10-1',
        ])->assertRedirect();

        $this->assertDatabaseHas('students', ['identificacion' => '2000001']);
    }
}
