<?php

declare(strict_types = 1);

namespace VinaiKopp\TechnicalDebtAggregate\Test\Integration;

use Magento\Framework\Console\CommandListInterface;
use Magento\Framework\ObjectManager\ConfigInterface as ObjectManagerConfig;
use Magento\TestFramework\ObjectManager;
use VinaiKopp\TechnicalDebtAggregate\Src\AggregateCollectorOutput;
use VinaiKopp\TechnicalDebtAggregate\Src\AggregateCollectorOutputInterface;
use VinaiKopp\TechnicalDebtAggregate\Src\CollectTechnicalDebtInfoCollectors;
use VinaiKopp\TechnicalDebtAggregate\Src\CollectTechnicalDebtInfoCollectorsInterface;
use VinaiKopp\TechnicalDebtAggregate\Src\Command\CollectTechnicalDebtCommand;
use VinaiKopp\TechnicalDebtAggregate\Src\CsvTechnicalDebtInfoFormatter;
use VinaiKopp\TechnicalDebtAggregate\Src\TechnicalDebtInfoCollector;
use VinaiKopp\TechnicalDebtAggregate\Src\TechnicalDebtInfoCollectorInterface;
use VinaiKopp\TechnicalDebtAggregate\Src\TechnicalDebtInfoFormatterInterface;

class TechnicalDebtAggregateDIConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return ObjectManagerConfig
     */
    private function getDiConfig()
    {
        return ObjectManager::getInstance()->get(ObjectManagerConfig::class);
    }

    private function assertPreference(string $expected, string $type)
    {
        $this->assertSame($expected, $this->getDiConfig()->getPreference($type));
    }

    public function testAggregateCollectorOutputPreference()
    {
        $this->assertPreference(AggregateCollectorOutput::class, AggregateCollectorOutputInterface::class);
    }

    public function testCollectTechnicalDebtInfoCollectorPreference()
    {
        $this->assertPreference(CollectTechnicalDebtInfoCollectors::class,
            CollectTechnicalDebtInfoCollectorsInterface::class);
    }

    public function testTechnicalDebtInfoCollectorPreference()
    {
        $this->assertPreference(TechnicalDebtInfoCollector::class, TechnicalDebtInfoCollectorInterface::class);
    }

    public function testTechnicalDebtInfoFormatterPreference()
    {
        $this->assertPreference(CsvTechnicalDebtInfoFormatter::class, TechnicalDebtInfoFormatterInterface::class);
    }

    public function testCommandIsRegistered()
    {
        /** @var CommandListInterface $commandList */
        $commandList = ObjectManager::getInstance()->create(CommandListInterface::class);
        $this->assertTrue(array_reduce($commandList->getCommands(), function ($found, $command) {
            return $found || $command instanceof CollectTechnicalDebtCommand;
        }), 'CollectTechnicalDebtCommand not registered');
    }
}
