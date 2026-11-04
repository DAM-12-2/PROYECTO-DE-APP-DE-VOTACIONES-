<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class PadronExportImportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->actingAs(User::where('name', 'admin')->first());
    }

    public function test_exportacion_xlsx_respeta_el_orden_del_piad(): void
    {
        Student::create([
            'identificacion' => '123456789',
            'nombre' => 'Jefferson',
            'apellidos' => 'Garro Rojas',
            'seccion' => '11-1',
        ]);

        $response = $this->get(route('admin.students.export.xlsx'));
        $response->assertStatus(200);

        $temporal = tempnam(sys_get_temp_dir(), 'padron') . '.xlsx';
        file_put_contents($temporal, $response->streamedContent());

        $hoja = IOFactory::load($temporal)->getActiveSheet();
        @unlink($temporal);

        $this->assertSame('Cédula', $hoja->getCell('A4')->getValue());
        $this->assertSame('Primer apellido', $hoja->getCell('B4')->getValue());
        $this->assertSame('Segundo apellido', $hoja->getCell('C4')->getValue());
        $this->assertSame('Nombre', $hoja->getCell('D4')->getValue());
        $this->assertSame('Sección', $hoja->getCell('E4')->getValue());
        $this->assertSame('Estado', $hoja->getCell('F4')->getValue());

        $this->assertSame('123456789', $hoja->getCell('A5')->getValue());
        $this->assertSame('Garro', $hoja->getCell('B5')->getValue());
        $this->assertSame('Rojas', $hoja->getCell('C5')->getValue());
        $this->assertSame('Jefferson', $hoja->getCell('D5')->getValue());
        $this->assertSame('Pendiente', $hoja->getCell('F5')->getValue());
    }

    public function test_exportacion_csv_respeta_el_orden_del_piad(): void
    {
        Student::create([
            'identificacion' => '123456789',
            'nombre' => 'Jefferson',
            'apellidos' => 'Garro Rojas',
            'seccion' => '11-1',
        ]);

        $contenido = $this->get(route('admin.students.export.csv'))->streamedContent();
        $lineas = preg_split('/\r\n|\n|\r/', trim($contenido));

        $this->assertSame(
            ['Cédula', 'Primer apellido', 'Segundo apellido', 'Nombre', 'Sección', 'Estado'],
            str_getcsv(ltrim($lineas[0], "\xEF\xBB\xBF"))
        );

        $fila = collect($lineas)->map(fn ($linea) => str_getcsv($linea))
            ->first(fn ($columnas) => ($columnas[0] ?? '') === '123456789');

        $this->assertSame(['123456789', 'Garro', 'Rojas', 'Jefferson', '11-1', 'Pendiente'], $fila);
    }

    public function test_importa_la_plantilla_excel_del_piad(): void
    {
        $spreadsheet = new Spreadsheet();
        $hoja = $spreadsheet->getActiveSheet();
        $hoja->setCellValue('A1', 'Indicaciones');
        $hoja->setCellValue('A5', 'Cédula');
        $hoja->setCellValue('B5', 'Primer apellido');
        $hoja->setCellValue('C5', 'Segundo apellido');
        $hoja->setCellValue('D5', 'Nombre');
        $hoja->setCellValue('E5', 'Sección');
        $hoja->fromArray(['12-345-678', 'Garro', 'Rojas', 'Jefferson', '11-1'], null, 'A7');
        $hoja->fromArray(['987654321', 'Mora', 'Vargas', 'Sahir', '11-2'], null, 'A8');

        ob_start();
        (new Xlsx($spreadsheet))->save('php://output');
        $binario = ob_get_clean();
        $spreadsheet->disconnectWorksheets();

        $file = UploadedFile::fake()->createWithContent('Plantilla Importar Estudiantes.xlsx', $binario);

        $this->post(route('admin.students.import'), ['csv_file' => $file])
            ->assertStatus(200)
            ->assertJsonPath('insertados', 2)
            ->assertJsonPath('rechazados', 0);

        $this->assertDatabaseHas('students', [
            'identificacion' => '12345678',
            'nombre' => 'Jefferson',
            'apellidos' => 'Garro Rojas',
            'seccion' => '11-1',
        ]);

        $this->assertDatabaseHas('students', [
            'identificacion' => '987654321',
            'nombre' => 'Sahir',
            'apellidos' => 'Mora Vargas',
        ]);
    }

    public function test_importa_el_formato_csv_anterior(): void
    {
        $csv = "identificacion,nombre,apellidos,seccion\n"
            . "111111111,Ana,Perez Mora,10-1\n";

        $file = UploadedFile::fake()->createWithContent('padron.csv', $csv);

        $this->post(route('admin.students.import'), ['csv_file' => $file])
            ->assertStatus(200)
            ->assertJsonPath('insertados', 1);

        $this->assertDatabaseHas('students', [
            'identificacion' => '111111111',
            'nombre' => 'Ana',
            'apellidos' => 'Perez Mora',
            'seccion' => '10-1',
        ]);
    }

    public function test_importa_csv_con_punto_y_coma(): void
    {
        $csv = "Cédula;Primer apellido;Segundo apellido;Nombre;Sección\n"
            . "222222222;Solano;Jiménez;María;12-3\n";

        $file = UploadedFile::fake()->createWithContent('padron.csv', $csv);

        $this->post(route('admin.students.import'), ['csv_file' => $file])
            ->assertStatus(200)
            ->assertJsonPath('insertados', 1);

        $this->assertDatabaseHas('students', [
            'identificacion' => '222222222',
            'nombre' => 'María',
            'apellidos' => 'Solano Jiménez',
            'seccion' => '12-3',
        ]);
    }
}
