<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class NumeroALetrasTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $base = dirname(__DIR__, 2);
        require_once $base . '/app/Helpers/NumeroALetras.php';
    }

    private function convertir(float $numero): string
    {
        return \App\Helpers\NumeroALetras::convertir($numero);
    }

    public function test_zero(): void
    {
        $this->assertSame('cero', $this->convertir(0));
    }

    public function test_small_number(): void
    {
        $this->assertSame('cinco', $this->convertir(5));
    }

    public function test_hundred(): void
    {
        $this->assertSame('cien', $this->convertir(100));
    }

    public function test_thousand(): void
    {
        $this->assertSame('mil', $this->convertir(1000));
    }

    public function test_complex_number(): void
    {
        $this->assertSame('mil doscientos treinta y cuatro', $this->convertir(1234));
    }

    public function test_with_decimals(): void
    {
        $this->assertSame('mil doscientos treinta y cuatro', $this->convertir(1234.56));
    }
}
