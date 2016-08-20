<?php

declare(strict_types = 1);

namespace VinaiKopp\TechnicalDebtAggregate\Src;

interface TechnicalDebtInfoFormatterInterface
{
    /**
     * @param array[] $data
     * @return string
     */
    public function format(array $data): string;
}

