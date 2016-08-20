<?php

declare(strict_types = 1);

namespace VinaiKopp\TechnicalDebtAggregate\Test\Unit;

use VinaiKopp\TechnicalDebtAggregate\Src\CsvTechnicalDebtInfoFormatter;
use VinaiKopp\TechnicalDebtAggregate\Src\TechnicalDebtInfoFormatterInterface;

class CsvTechnicalDebtInfoFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsTechnicalDebtInfoFormatter()
    {
        $this->assertInstanceOf(TechnicalDebtInfoFormatterInterface::class, new CsvTechnicalDebtInfoFormatter());
    }

    public function testReturnsDataAsCsvString()
    {
        $sourceData = [
            ['A', 'B', 'C'],
            ['D', 'E', 'F'],
        ];
        $expectedCsv = <<<EOCSV
A,B,C
D,E,F

EOCSV;
        $this->assertSame($expectedCsv, (new CsvTechnicalDebtInfoFormatter())->format($sourceData));
    }
}
