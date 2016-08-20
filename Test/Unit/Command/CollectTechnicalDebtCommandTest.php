<?php

declare(strict_types = 1);

namespace VinaiKopp\TechnicalDebtAggregate\Test\Unit\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use VinaiKopp\TechnicalDebtAggregate\Src\Command\CollectTechnicalDebtCommand;
use VinaiKopp\TechnicalDebtAggregate\Src\TechnicalDebtInfo;

class CollectTechnicalDebtCommandTest extends \PHPUnit_Framework_TestCase
{
    private $dummyOuputData = <<<EOCDV
Module,Dummy Metric A, Dummy Metric B
Foo_Bar,80,0
EOCDV;

    /**
     * @var TechnicalDebtInfo|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockTechnicalDebtInfo;

    /**
     * @var InputInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $stubInput;

    /**
     * @var OutputInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockOutput;

    private function createCommand()
    {
        return new CollectTechnicalDebtCommand($this->mockTechnicalDebtInfo);
    }

    protected function setUp()
    {
        $this->mockTechnicalDebtInfo = $this->getMock(TechnicalDebtInfo::class, [], [], '', false);
        $this->stubInput = $this->getMock(InputInterface::class);
        $this->mockOutput = $this->getMock(OutputInterface::class);
    }

    public function testExtendsCommand()
    {
        $this->assertInstanceOf(Command::class, $this->createCommand());
    }

    public function testHasName()
    {
        $this->assertSame('dev:technical-debt:collect', $this->createCommand()->getName());
    }

    public function testHasDescription()
    {
        $this->assertNotEmpty($this->createCommand()->getDescription());
    }

    public function testTakesOptionalFileNameArgument()
    {
        $argument = $this->createCommand()->getDefinition()->getArgument('file');
        $this->assertNotNull($argument);
        $this->assertFalse($argument->isRequired());
        $this->assertNotEmpty($argument->getDescription());
        $this->assertSame('STDOUT', $argument->getDefault());
    }

    public function testOutputsTechnicalDebtInfoToStdOut()
    {
        $this->mockTechnicalDebtInfo->method('asString')->willReturn($this->dummyOuputData);
        $this->stubInput->method('getArgument')->with('file')->willReturn('STDOUT');
        $this->mockOutput->expects($this->once())
            ->method('write')
            ->with($this->dummyOuputData, false, OutputInterface::OUTPUT_RAW);
        
        $this->createCommand()->run($this->stubInput, $this->mockOutput);
    }

    public function testOutputsTechnicalDebtInfoToFile()
    {
        $this->mockTechnicalDebtInfo->expects($this->once())->method('toFile')->with('foo.csv');
        $this->stubInput->method('getArgument')->with('file')->willReturn('foo.csv');
        $this->mockOutput->expects($this->once())
            ->method('writeln')
            ->with('<info>CSV written to file "foo.csv"</info>');
        
        $this->createCommand()->run($this->stubInput, $this->mockOutput);
    }
}
