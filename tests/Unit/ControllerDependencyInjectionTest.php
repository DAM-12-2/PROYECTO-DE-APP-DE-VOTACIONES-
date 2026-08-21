<?php

namespace Tests\Unit;

use App\Http\Controllers\ReportController;
use App\Services\ElectionService;
use App\Services\InstitutionService;
use App\Services\VoteTallyService;

class ControllerDependencyInjectionTest extends \Tests\TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;
    public function test_report_controller_assigns_injected_services_in_constructor(): void
    {
        $institutionService = new InstitutionService();
        $voteTallyService = new VoteTallyService();
        $electionService = new ElectionService($voteTallyService);

        $controller = new ReportController($institutionService, $electionService, $voteTallyService);

        $reflection = new \ReflectionClass($controller);

        $institutionProperty = $reflection->getProperty('institutionService');
        $institutionProperty->setAccessible(true);

        $electionProperty = $reflection->getProperty('electionService');
        $electionProperty->setAccessible(true);

        $voteTallyProperty = $reflection->getProperty('voteTallyService');
        $voteTallyProperty->setAccessible(true);

        $this->assertSame($institutionService, $institutionProperty->getValue($controller));
        $this->assertSame($electionService, $electionProperty->getValue($controller));
        $this->assertSame($voteTallyService, $voteTallyProperty->getValue($controller));
    }
}
