<?php

declare(strict_types = 1);

namespace VinaiKopp\TechnicalDebtAggregate\Src;

class CsvTechnicalDebtInfoFormatter implements TechnicalDebtInfoFormatterInterface
{
    /**
     * @param array[] $data
     * @return string
     */
    public function format(array $data): string
    {
        $tmpFileHandle = fopen('php://memory', 'w+');
        
        $this->writeCsvToFileHandle($data, $tmpFileHandle);
        $csvString = $this->getFileHandleContents($tmpFileHandle);
        
        fclose($tmpFileHandle);
        return $csvString;
    }

    /**
     * @param array[] $data
     * @param resource $tmpFileHandle
     */
    private function writeCsvToFileHandle(array $data, $tmpFileHandle)
    {
        foreach ($data as $row) {
            fputcsv($tmpFileHandle, $row);
        }
    }

    /**
     * @param resource $tmpFileHandle
     * @return string
     */
    private function getFileHandleContents($tmpFileHandle): string
    {
        rewind($tmpFileHandle);
        $csvString = '';
        while (false !== ($line = fgets($tmpFileHandle))) {
            $csvString .= $line;
        }
        return $csvString;
    }
}
