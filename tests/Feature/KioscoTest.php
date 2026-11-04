<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KioscoTest extends TestCase
{
    use RefreshDatabase;

    public function test_kiosco_renderiza_cronometro_con_tiempo_por_defecto(): void
    {
        $response = $this->get('/kiosko');

        $response->assertStatus(200);
        $response->assertSee('data-tiempo-votacion="90"', false);
        $response->assertSee('id="tiempo-restante"', false);
        $response->assertSee('id="btn-blanco"', false);
        $response->assertSee('/js/voting.js', false);
    }

    public function test_kiosco_usa_el_tiempo_configurado(): void
    {
        Setting::updateOrCreate(['nombre' => 'tiempo_votacion'], ['detalle' => '45']);

        $response = $this->get('/kiosko');

        $response->assertSee('data-tiempo-votacion="45"', false);
    }
}
