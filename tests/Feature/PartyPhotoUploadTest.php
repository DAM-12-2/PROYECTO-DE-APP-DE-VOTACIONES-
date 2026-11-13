<?php

namespace Tests\Feature;

use App\Models\Party;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PartyPhotoUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->actingAs(User::where('name', 'admin')->first());
    }

    public function test_guarda_bandera_y_foto_del_presidente(): void
    {
        $this->post('/admin/partidos', [
            'siglas' => 'TST',
            'nombre' => 'Partido de Prueba',
            'bandera' => UploadedFile::fake()->image('bandera.png', 240, 160),
            'fotopresidente' => UploadedFile::fake()->image('presidente.jpg', 160, 160),
        ])->assertRedirect(route('admin.parties'));

        $party = Party::where('siglas', 'TST')->firstOrFail();

        $this->assertNotNull($party->bandera);
        $this->assertNotNull($party->fotopresidente);
        $this->assertFileExists(public_path($party->bandera));
        $this->assertFileExists(public_path($party->fotopresidente));

        @unlink(public_path($party->bandera));
        @unlink(public_path($party->fotopresidente));
    }

    public function test_reemplaza_la_bandera_sin_dejar_el_archivo_anterior(): void
    {
        $this->post('/admin/partidos', [
            'siglas' => 'TST',
            'nombre' => 'Partido de Prueba',
            'bandera' => UploadedFile::fake()->image('bandera.png', 240, 160),
        ])->assertRedirect(route('admin.parties'));

        $party = Party::where('siglas', 'TST')->firstOrFail();
        $anterior = public_path($party->bandera);

        $this->put('/admin/partidos/' . $party->id, [
            'siglas' => 'TST',
            'nombre' => 'Partido de Prueba',
            'bandera' => UploadedFile::fake()->image('bandera-nueva.png', 240, 160),
        ])->assertRedirect(route('admin.parties'));

        $party->refresh();
        $this->assertFileDoesNotExist($anterior);
        $this->assertFileExists(public_path($party->bandera));

        @unlink(public_path($party->bandera));
    }
}
