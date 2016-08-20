<?php

declare(strict_types = 1);

namespace VinaiKopp\TechnicalDebtAggregate\Src;

interface AggregateCollectorOutputInterface
{
    /**
     * @param TechnicalDebtInfoCollectorInterface[] $technicalDebtInfoCollectors
     * @return array[]
     */
    public function aggregate(TechnicalDebtInfoCollectorInterface ...$technicalDebtInfoCollectors) : array;
}
