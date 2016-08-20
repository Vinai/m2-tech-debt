<?php

namespace VinaiKopp\TechnicalDebtAggregate\Test\Unit;

use VinaiKopp\TechnicalDebtAggregate\Src\AggregateCollectorOutputInterface;
use VinaiKopp\TechnicalDebtAggregate\Src\CollectTechnicalDebtInfoCollectorsInterface;
use VinaiKopp\TechnicalDebtAggregate\Src\TechnicalDebtInfo;
use VinaiKopp\TechnicalDebtAggregate\Src\TechnicalDebtInfoFormatterInterface;

class TechnicalDebtInfoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AggregateCollectorOutputInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $stubAggregateCollectorOutput;

    /**
     * @var TechnicalDebtInfoFormatterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockTechnicalDebtInfoFormatter;

    /**
     * @var CollectTechnicalDebtInfoCollectorsInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $stubCollectCollectors;

    /**
     * @var string
     */
    private $dummyFileName;

    private function createInstance()
    {
        return new TechnicalDebtInfo(
            $this->stubAggregateCollectorOutput,
            $this->stubCollectCollectors,
            $this->mockTechnicalDebtInfoFormatter
        );
    }

    protected function setUp()
    {
        $this->stubAggregateCollectorOutput = $this->getMock(AggregateCollectorOutputInterface::class);
        $this->stubCollectCollectors = $this->getMock(CollectTechnicalDebtInfoCollectorsInterface::class);
        $this->mockTechnicalDebtInfoFormatter = $this->getMock(TechnicalDebtInfoFormatterInterface::class);
        $this->dummyFileName = tempnam(sys_get_temp_dir(), 'test-');
    }

    protected function tearDown()
    {
        if (file_exists($this->dummyFileName)) {
            unlink($this->dummyFileName);
        }
    }

    public function testReturnsTechnicalDebtAsString()
    {
        $this->mockTechnicalDebtInfoFormatter->method('format')->willReturn('foo');
        $this->assertSame('foo', $this->createInstance()->asString());
    }

    public function testWritesDataToGivenFile()
    {
        $this->mockTechnicalDebtInfoFormatter->method('format')->willReturn('foo');
        $this->createInstance()->toFile($this->dummyFileName);
        $this->assertFileExists($this->dummyFileName);
        $message = sprintf('File %s does not have the expected content "%s"', $this->dummyFileName, 'foo');
        $this->assertSame('foo', file_get_contents($this->dummyFileName), $message);
    }
}
