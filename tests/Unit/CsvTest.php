<?php

namespace Tests\Unit;

use App\Support\Csv;
use PHPUnit\Framework\TestCase;

class CsvTest extends TestCase
{
    public function test_neutraliza_formulas(): void
    {
        $this->assertSame("'=CMD()", Csv::sanitize('=CMD()'));
        $this->assertSame("'+1", Csv::sanitize('+1'));
        $this->assertSame("'-1", Csv::sanitize('-1'));
        $this->assertSame("'@x", Csv::sanitize('@x'));
    }

    public function test_quita_saltos_de_linea(): void
    {
        $this->assertSame('a b', Csv::sanitize("a\nb"));
    }

    public function test_no_altera_texto_normal(): void
    {
        $this->assertSame('Ana Perez', Csv::sanitize('Ana Perez'));
    }
}
