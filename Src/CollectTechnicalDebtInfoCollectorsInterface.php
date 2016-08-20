<?php

declare(strict_types = 1);

namespace VinaiKopp\TechnicalDebtAggregate\Src;

interface CollectTechnicalDebtInfoCollectorsInterface
{
    /**
     * @return TechnicalDebtInfoCollectorInterface[]
     */
    public function collect() : array;
}
