<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SeguridadLocalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        Setting::updateOrCreate(['nombre' => 'eleccion_abierta'], ['detalle' => '1']);
    }

    public function test_headers_de_seguridad_presentes(): void
    {
        $response = $this->get('/kiosko');

        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'no-referrer');
        $response->assertHeader('Content-Security-Policy');
    }

    public function test_vistas_sin_recursos_externos(): void
    {
        $infractores = [];

        foreach (\Illuminate\Support\Facades\File::allFiles(resource_path('views')) as $archivo) {
            $contenido = $archivo->getContents();

            if (preg_match('/src="https?:\/\//', $contenido)
                || preg_match('/<link[^>]+href="https?:\/\//', $contenido)) {
                $infractores[] = $archivo->getRelativePathname();
            }
        }

        $this->assertSame([], $infractores);
    }

    public function test_assets_locales_existen(): void
    {
        foreach ([
            'js/tailwind.js',
            'js/chart.min.js',
            'js/pusher.min.js',
            'js/echo.iife.js',
            'js/realtime.js',
            'js/voting.js',
            'css/fuentes.css',
        ] as $asset) {
            $this->assertFileExists(public_path($asset), "Falta el asset local {$asset}");
        }
    }

    public function test_hash_usa_argon2id(): void
    {
        $hash = Hash::make('PruebaSegura2026');

        $this->assertStringStartsWith('$argon2id$', $hash);
    }

    public function test_jrv_no_ve_estudiantes_de_otra_mesa(): void
    {
        $this->actingAs(User::where('name', 'jrv')->first());

        // 1000005 pertenece a la sección 12-1 (mesa 2); el JRV es de la mesa 1.
        $this->getJson('/jrv/api/buscar?identificacion=1000005')->assertStatus(404);
        $this->getJson('/jrv/api/buscar?identificacion=1000001')->assertStatus(200);
    }

    public function test_registro_rechaza_estudiante_de_otra_mesa(): void
    {
        $this->actingAs(User::where('name', 'jrv')->first());

        $this->postJson('/registro/solicitud', ['identificacion' => '1000005'])
            ->assertStatus(403);
    }

    public function test_jrv_no_puede_ver_solicitud_de_otra_mesa(): void
    {
        $jrv = User::where('name', 'jrv')->first();
        $this->actingAs($jrv);

        $estudiante = \App\Models\Student::where('identificacion', '1000005')->first();
        $solicitud = \App\Models\VoteRequest::create([
            'student_id' => $estudiante->id,
            'mesa_id' => 2,
            'estado' => 'pendiente',
            'created_by' => $jrv->id,
        ]);

        $this->getJson("/registro/solicitud/{$solicitud->id}/estado")->assertStatus(403);
    }
}
