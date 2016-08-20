<?php

declare(strict_types = 1);

namespace VinaiKopp\TechnicalDebtAggregate\Src;

class TechnicalDebtInfo
{
    /**
     * @var AggregateCollectorOutputInterface
     */
    private $aggregateCollectorOutput;

    /**
     * @var CollectTechnicalDebtInfoCollectorsInterface
     */
    private $collectTechnicalDebtInfoCollectors;

    /**
     * @var TechnicalDebtInfoFormatterInterface
     */
    private $technicalDebtInfoFormatter;

    public function __construct(
        AggregateCollectorOutputInterface $aggregateCollectorOutput,
        CollectTechnicalDebtInfoCollectorsInterface $collectTechnicalDebtInfoCollectors,
        TechnicalDebtInfoFormatterInterface $technicalDebtInfoFormatter
    ) {
        $this->aggregateCollectorOutput = $aggregateCollectorOutput;
        $this->collectTechnicalDebtInfoCollectors = $collectTechnicalDebtInfoCollectors;
        $this->technicalDebtInfoFormatter = $technicalDebtInfoFormatter;
    }

    public function asString(): string
    {
        $data = $this->aggregateCollectorOutput->aggregate(...$this->collectTechnicalDebtInfoCollectors->collect());
        return $this->technicalDebtInfoFormatter->format($data);
    }

    public function toFile(string $file)
    {
        file_put_contents($file, $this->asString());
    }
}
