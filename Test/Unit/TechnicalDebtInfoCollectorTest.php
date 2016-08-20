<?php

declare(strict_types = 1);

namespace VinaiKopp\TechnicalDebtAggregate;

use VinaiKopp\TechnicalDebtAggregate\Src\Exception\InvalidCollectorScriptException;
use VinaiKopp\TechnicalDebtAggregate\Src\TechnicalDebtInfoCollector;

class TechnicalDebtInfoCollectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $nonExecutableFile;

    /**
     * @var string
     */
    private $validCollectorScript = __DIR__ . '/StubCollectors/EchoCollector.sh';

    protected function setUp()
    {
        $this->nonExecutableFile = tempnam(sys_get_temp_dir(), 'test-');
        chmod($this->nonExecutableFile, 0600);
    }

    protected function tearDown()
    {
        unlink($this->nonExecutableFile);
    }
    
    public function testThrowsExceptionIfCollectorNotExecutable()
    {
        $basename = basename($this->nonExecutableFile);
        $expectedMessage = sprintf('The technical debt collector "%s" is not executable', $basename);
        $this->setExpectedException(InvalidCollectorScriptException::class, $expectedMessage);
        
        TechnicalDebtInfoCollector::fromFilePath($this->nonExecutableFile);
    }

    public function testThrowsExceptionIfCollectorNotExists()
    {
        $expectedMessage = 'Technical debt collector "does/not/exist" is not found';
        $this->setExpectedException(InvalidCollectorScriptException::class, $expectedMessage);

        TechnicalDebtInfoCollector::fromFilePath('does/not/exist');
    }

    public function testReturnsScriptFilePathAndArgsWhenCastToString()
    {
        $script = $this->validCollectorScript;
        $technicalDebtInfoCollector = TechnicalDebtInfoCollector::fromFilePath($script);
        $this->assertEquals($script, $technicalDebtInfoCollector->command());
    }

    public function testReturnsCollectorNameResult()
    {
        $technicalDebtInfoCollector = TechnicalDebtInfoCollector::fromFilePath($this->validCollectorScript);
        $this->assertSame('Testing Echo Collector', $technicalDebtInfoCollector->name());
    }

    public function testReturnsTheCollectorOutput()
    {
        $collector = TechnicalDebtInfoCollector::fromFilePath($this->validCollectorScript);
        $this->assertSame(__DIR__, $collector->collectForModule(__DIR__));
    }
}
