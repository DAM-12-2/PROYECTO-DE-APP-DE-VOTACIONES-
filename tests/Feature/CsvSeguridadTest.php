<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CsvSeguridadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->actingAs(User::where('name', 'admin')->first());
    }

    public function test_importacion_sanea_html_y_digitos(): void
    {
        $csv = "identificacion,nombre,apellidos,seccion\n"
            . "12-34-56,<script>Ana</script>,Perez,11-1\n"
            . ",Sin,Identificacion,11-1\n";

        $file = UploadedFile::fake()->createWithContent('padron.csv', $csv);

        $this->post('/admin/estudiantes/importar', ['csv_file' => $file])
            ->assertStatus(200)
            ->assertJsonPath('insertados', 1)
            ->assertJsonPath('rechazados', 1);

        $this->assertDatabaseHas('students', [
            'identificacion' => '123456',
            'nombre' => 'Ana',
        ]);
    }

    public function test_exportacion_neutraliza_formulas(): void
    {
        Student::create([
            'identificacion' => '7777777',
            'nombre' => '=CMD()',
            'apellidos' => 'Prueba',
            'seccion' => '11-1',
        ]);

        $response = $this->get('/api/students/export');
        $response->assertStatus(200);
        $contenido = $response->streamedContent();

        $this->assertStringContainsString("'=CMD()", $contenido);
    }
}
