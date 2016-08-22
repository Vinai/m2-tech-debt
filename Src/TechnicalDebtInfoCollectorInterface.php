<?php

declare(strict_types = 1);

namespace VinaiKopp\TechnicalDebtAggregate\Src;

interface TechnicalDebtInfoCollectorInterface
{
    public function collectForModule(string $moduleDir) : string;

    public function name() : string;
}
