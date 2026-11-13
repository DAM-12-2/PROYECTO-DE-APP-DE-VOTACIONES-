<?php

namespace Tests\Feature;

use App\Models\Party;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;
use ZipArchive;

class PartyPhotoBulkImportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->actingAs(User::where('name', 'admin')->first());
    }

    protected function tearDown(): void
    {
        foreach (Party::all() as $party) {
            foreach (['bandera', 'fotopresidente'] as $campo) {
                if ($party->{$campo} && is_file(public_path($party->{$campo}))) {
                    @unlink(public_path($party->{$campo}));
                }
            }
        }

        parent::tearDown();
    }

    public function test_importa_fotos_por_el_nombre_de_archivo(): void
    {
        $zip = $this->crearZip([
            'PVA_bandera.png' => $this->imagen('bandera.png', 240, 160),
            'PVA_presidente.jpg' => $this->imagen('presidente.jpg', 160, 160),
        ]);

        $this->post(route('admin.parties.import_photos'), [
            'zip_file' => UploadedFile::fake()->createWithContent('fotos.zip', $zip),
        ])->assertRedirect(route('admin.parties'))->assertSessionHas('success');

        $party = Party::where('siglas', 'PVA')->firstOrFail();

        $this->assertNotNull($party->bandera);
        $this->assertNotNull($party->fotopresidente);
        $this->assertFileExists(public_path($party->bandera));
        $this->assertFileExists(public_path($party->fotopresidente));
    }

    public function test_importa_fotos_con_manifiesto_csv(): void
    {
        $zip = $this->crearZip([
            'bandera-personalizada.png' => $this->imagen('bandera.png', 240, 160),
            'presidente-personalizado.jpg' => $this->imagen('presidente.jpg', 160, 160),
            'manifest.csv' => "archivo,siglas,tipo\nbandera-personalizada.png,PVA,bandera\npresidente-personalizado.jpg,PVA,presidente\n",
        ]);

        $this->post(route('admin.parties.import_photos'), [
            'zip_file' => UploadedFile::fake()->createWithContent('fotos.zip', $zip),
        ])->assertRedirect(route('admin.parties'))->assertSessionHas('success');

        $party = Party::where('siglas', 'PVA')->firstOrFail();

        $this->assertNotNull($party->bandera);
        $this->assertNotNull($party->fotopresidente);
        $this->assertFileExists(public_path($party->bandera));
        $this->assertFileExists(public_path($party->fotopresidente));
    }

    public function test_importa_fotos_con_manifiesto_excel(): void
    {
        $spreadsheet = new Spreadsheet();
        $hoja = $spreadsheet->getActiveSheet();
        $hoja->fromArray(['archivo', 'siglas', 'tipo'], null, 'A1');
        $hoja->fromArray(['bandera-excel.png', 'PVA', 'bandera'], null, 'A2');
        $hoja->fromArray(['presidente-excel.jpg', 'PVA', 'presidente'], null, 'A3');

        ob_start();
        (new Xlsx($spreadsheet))->save('php://output');
        $manifiesto = ob_get_clean();
        $spreadsheet->disconnectWorksheets();

        $zip = $this->crearZip([
            'bandera-excel.png' => $this->imagen('bandera.png', 240, 160),
            'presidente-excel.jpg' => $this->imagen('presidente.jpg', 160, 160),
            'manifest.xlsx' => $manifiesto,
        ]);

        $this->post(route('admin.parties.import_photos'), [
            'zip_file' => UploadedFile::fake()->createWithContent('fotos.zip', $zip),
        ])->assertRedirect(route('admin.parties'))->assertSessionHas('success');

        $party = Party::where('siglas', 'PVA')->firstOrFail();

        $this->assertNotNull($party->bandera);
        $this->assertNotNull($party->fotopresidente);
        $this->assertFileExists(public_path($party->bandera));
        $this->assertFileExists(public_path($party->fotopresidente));
    }

    public function test_reporta_siglas_inexistentes_y_archivos_invalidos(): void
    {
        $zip = $this->crearZip([
            'NOEXISTE_bandera.png' => $this->imagen('bandera.png', 240, 160),
            'PVA_bandera.txt' => 'no es una imagen',
        ]);

        $this->post(route('admin.parties.import_photos'), [
            'zip_file' => UploadedFile::fake()->createWithContent('fotos.zip', $zip),
        ])->assertRedirect(route('admin.parties'))->assertSessionHas('error');

        $this->assertNull(Party::where('siglas', 'PVA')->firstOrFail()->bandera);
    }

    public function test_rechaza_un_zip_invalido(): void
    {
        $this->post(route('admin.parties.import_photos'), [
            'zip_file' => UploadedFile::fake()->createWithContent('fotos.zip', 'esto no es un zip'),
        ])->assertRedirect(route('admin.parties'))->assertSessionHas('error');
    }

    private function crearZip(array $archivos): string
    {
        $ruta = tempnam(sys_get_temp_dir(), 'fotos_partidos_') . '.zip';
        $zip = new ZipArchive();

        $this->assertTrue($zip->open($ruta, ZipArchive::CREATE | ZipArchive::OVERWRITE));

        foreach ($archivos as $nombre => $contenido) {
            $zip->addFromString($nombre, $contenido);
        }

        $zip->close();
        $contenido = (string) file_get_contents($ruta);
        @unlink($ruta);

        return $contenido;
    }

    private function imagen(string $nombre, int $ancho, int $alto): string
    {
        $archivo = UploadedFile::fake()->image($nombre, $ancho, $alto);

        return (string) file_get_contents($archivo->getRealPath());
    }
}
