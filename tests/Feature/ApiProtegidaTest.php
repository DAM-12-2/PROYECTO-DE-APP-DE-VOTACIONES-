<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiProtegidaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_padron_api_requiere_autenticacion(): void
    {
        $this->getJson('/api/students')->assertStatus(401);
        $this->postJson('/api/students', ['identificacion' => '1'])->assertStatus(401);
        $this->getJson('/api/students/export')->assertStatus(401);
    }

    public function test_votar_api_requiere_autenticacion(): void
    {
        $this->postJson('/api/votar', ['identificacion' => '1000001', 'party_id' => 1])
            ->assertStatus(401);
    }

    public function test_ganador_requiere_autenticacion(): void
    {
        $this->getJson('/api/ganador')->assertStatus(401);
    }
}
