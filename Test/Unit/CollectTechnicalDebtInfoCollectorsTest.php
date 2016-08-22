<?php

declare(strict_types = 1);

namespace VinaiKopp\TechnicalDebtAggregate\Test\Unit;

use VinaiKopp\TechnicalDebtAggregate\Src\Exception\InvalidCollectorDirectoryException;
use VinaiKopp\TechnicalDebtAggregate\Src\CollectTechnicalDebtInfoCollectors;
use VinaiKopp\TechnicalDebtAggregate\Src\TechnicalDebtInfoCollector;

class CollectTechnicalDebtInfoCollectorsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $testDirPath;

    protected function setUp()
    {
        $this->testDirPath = sys_get_temp_dir() . '/' . uniqid('test-');
        mkdir($this->testDirPath, 0700, true);
    }
    
    protected function tearDown()
    {
        rmdir($this->testDirPath);
    }

    public function testThrowsExceptionIfDirectoryDoesNotExist()
    {
        $notDirectory = __DIR__ . '/does/not/exist';
        $expectedMessage = sprintf('Technical Debt Info Collector Script Directory "%s" not found', $notDirectory);
        $this->setExpectedException(InvalidCollectorDirectoryException::class, $expectedMessage);
        
        CollectTechnicalDebtInfoCollectors::inDirectory($notDirectory);
    }

    public function testThrowsExceptionIfDirectoryNotReadable()
    {
        $directory = $this->testDirPath;
        $expectedMessage = sprintf('Technical Debt Info Collector Script Directory "%s" not readable', $directory);
        $this->setExpectedException(InvalidCollectorDirectoryException::class, $expectedMessage);

        chmod($this->testDirPath, 0300);
        
        new CollectTechnicalDebtInfoCollectors($directory);
    }

    public function testThrowsExceptionIfNotDirectory()
    {
        $expectedMessage = sprintf('Technical Debt Info Collector Script Directory "%s" is not a directory', __FILE__);
        $this->setExpectedException(InvalidCollectorDirectoryException::class, $expectedMessage);

        new CollectTechnicalDebtInfoCollectors(__FILE__);
    }

    public function testReturnsArrayOfCollectors()
    {
        $collectorCollector = new CollectTechnicalDebtInfoCollectors(__DIR__ . '/StubCollectors');
        $collectors = $collectorCollector->collect();
        $this->assertInternalType('array', $collectors);
        $this->assertNotEmpty($collectors);
        $this->assertContainsOnlyInstancesOf(TechnicalDebtInfoCollector::class, $collectors);
    }

    public function testIgnoresReadmeFile()
    {
        $collectorCollector = new CollectTechnicalDebtInfoCollectors(__DIR__ . '/StubCollectors');
        $collectorCollector->collect();
        
        $this->assertFileExists(__DIR__ . '/StubCollectors/README.md');
        
    }
}
