<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VotacionJrvTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    private function actingAsJrv(): void
    {
        $jrv = User::where('name', 'jrv')->first();
        $this->actingAs($jrv);
    }

    public function test_jrv_index(): void
    {
        $this->actingAsJrv();
        $response = $this->get('/jrv');
        $response->assertStatus(200);
    }

    public function test_jrv_search_student_found(): void
    {
        $this->actingAsJrv();
        $response = $this->getJson('/jrv/api/buscar?identificacion=1000001');
        $response->assertStatus(200);
    }

    public function test_jrv_search_student_not_found(): void
    {
        $this->actingAsJrv();
        $response = $this->getJson('/jrv/api/buscar?identificacion=9999999');
        $response->assertStatus(404);
    }

    public function test_jrv_list_partidos(): void
    {
        $this->actingAsJrv();
        $response = $this->getJson('/jrv/api/partidos');
        $response->assertStatus(200);
    }
}
