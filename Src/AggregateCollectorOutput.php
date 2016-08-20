<?php

declare(strict_types = 1);

namespace VinaiKopp\TechnicalDebtAggregate\Src;

use Magento\Framework\Component\ComponentRegistrar;

class AggregateCollectorOutput implements AggregateCollectorOutputInterface
{
    /**
     * @var ComponentRegistrar
     */
    private $componentRegistrar;

    public function __construct(ComponentRegistrar $componentRegistrar)
    {
        $this->componentRegistrar = $componentRegistrar;
    }

    /**
     * @param TechnicalDebtInfoCollectorInterface[] $technicalDebtInfoCollectors
     * @return array[]
     */
    public function aggregate(TechnicalDebtInfoCollectorInterface ...$technicalDebtInfoCollectors): array
    {
        return array_merge(
            [$this->collectCollectorNames($technicalDebtInfoCollectors)],
            $this->collectTechnicalDebtInfoForModules($technicalDebtInfoCollectors)
        );
    }

    /**
     * @param TechnicalDebtInfoCollectorInterface[] $technicalDebtInfoCollectors
     * @return string[]
     */
    private function collectCollectorNames(array $technicalDebtInfoCollectors)
    {
        return array_map(function (TechnicalDebtInfoCollectorInterface $collector) {
            return $collector->name();
        }, $technicalDebtInfoCollectors);
    }

    /**
     * @param TechnicalDebtInfoCollectorInterface[] $technicalDebtInfoCollectors
     * @return array[]
     */
    private function collectTechnicalDebtInfoForModules(array $technicalDebtInfoCollectors)
    {
        return array_map(function (string $modulePath) use ($technicalDebtInfoCollectors) {
            return $this->invokeCollectorsForModule($technicalDebtInfoCollectors, $modulePath);
        }, $this->modulePaths());
    }

    /**
     * @param TechnicalDebtInfoCollectorInterface[] $technicalDebtInfoCollectors
     * @param string $modulePath
     * @return string[]
     */
    private function invokeCollectorsForModule(array $technicalDebtInfoCollectors, string $modulePath): array
    {
        return array_map(function (TechnicalDebtInfoCollectorInterface $collector) use ($modulePath) {
            return $collector->collectForModule($modulePath);
        }, $technicalDebtInfoCollectors);
    }

    /**
     * @return string[]
     */
    private function modulePaths()
    {
        return array_values($this->componentRegistrar->getPaths(ComponentRegistrar::MODULE));
    }
}
