<?php

declare(strict_types = 1);

namespace VinaiKopp\TechnicalDebtAggregate;

use Magento\Framework\Component\ComponentRegistrar;
use VinaiKopp\TechnicalDebtAggregate\Src\AggregateCollectorOutput;
use VinaiKopp\TechnicalDebtAggregate\Src\TechnicalDebtInfoCollectorInterface;

class AggregateCollectorOutputTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ComponentRegistrar|\PHPUnit_Framework_MockObject_MockObject
     */
    private $stubRegistrar;

    /**
     * @var TechnicalDebtInfoCollectorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $stubCollector;

    protected function setUp()
    {
        $this->stubRegistrar = $this->getMock(ComponentRegistrar::class);
        $this->stubCollector = $this->getMock(TechnicalDebtInfoCollectorInterface::class);
        $this->stubCollector->method('name')->willReturn('Stub Collector');
        $this->stubCollector->method('collectForModule')->willReturn('foo');
    }
    
    public function testCallsCollectorForOneModule()
    {
        $this->stubRegistrar->method('getPaths')->with(ComponentRegistrar::MODULE)->willReturn(['/path/to/module']);
        
        $aggregator = new AggregateCollectorOutput($this->stubRegistrar);

        $expected = [
            ['Stub Collector'],
            ['foo'],
        ];
        $this->assertSame($expected, $aggregator->aggregate($this->stubCollector));
    }
    
    public function testCallsCollectorForTwoModules()
    {
        $this->stubRegistrar->method('getPaths')->with(ComponentRegistrar::MODULE)->willReturn([
            '/path/to/moduleA',
            '/path/to/moduleB',
        ]);
        
        $aggregator = new AggregateCollectorOutput($this->stubRegistrar);

        $expected = [
            ['Stub Collector'],
            ['foo'],
            ['foo'],
        ];
        $this->assertSame($expected, $aggregator->aggregate($this->stubCollector));
    }
}
