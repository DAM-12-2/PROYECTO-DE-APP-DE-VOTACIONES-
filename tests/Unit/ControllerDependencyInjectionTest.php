<?php

namespace Tests\Unit;

use App\Http\Controllers\ReportController;
use App\Services\ElectionService;
use App\Services\InstitutionService;
use App\Services\VoteTallyService;
use PHPUnit\Framework\TestCase;

class ControllerDependencyInjectionTest extends TestCase
{
    public function test_report_controller_assigns_injected_services_in_constructor(): void
    {
        $institutionService = new InstitutionService();
        $electionService = new ElectionService();
        $voteTallyService = new VoteTallyService();

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
