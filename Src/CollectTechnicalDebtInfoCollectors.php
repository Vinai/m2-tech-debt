<?php

declare(strict_types = 1);

namespace VinaiKopp\TechnicalDebtAggregate\Src;

use VinaiKopp\TechnicalDebtAggregate\Src\Exception\InvalidCollectorDirectoryException;

class CollectTechnicalDebtInfoCollectors implements CollectTechnicalDebtInfoCollectorsInterface
{
    /**
     * @var string
     */
    private $collectorDirPath;

    public function __construct(string $collectorDirPath = null)
    {
        $this->validateCollectorDirectoryPath($collectorDirPath ?? __DIR__ . '/../Collectors');
    }

    private function validateCollectorDirectoryPath(string $collectorDirPath)
    {
        $this->validateDirExists($collectorDirPath);
        $this->validateDirIsReadable($collectorDirPath);
        $this->validateIsDirectory($collectorDirPath);
        $this->collectorDirPath = $collectorDirPath;
    }

    private function validateDirExists(string $dirPath)
    {
        if (! file_exists($dirPath)) {
            $message = sprintf('Technical Debt Info Collector Script Directory "%s" not found', $dirPath);
            throw new InvalidCollectorDirectoryException($message);
        }
    }

    private function validateDirIsReadable(string $dirPath)
    {
        if (! is_readable($dirPath)) {
            $message = sprintf('Technical Debt Info Collector Script Directory "%s" not readable', $dirPath);
            throw new InvalidCollectorDirectoryException($message);
        }
    }

    private function validateIsDirectory(string $dirPath)
    {
        if (! is_dir($dirPath)) {
            $message = sprintf('Technical Debt Info Collector Script Directory "%s" is not a directory', $dirPath);
            throw new InvalidCollectorDirectoryException($message);
        }
    }

    public static function inDirectory(string $collectorDirPath): self
    {
        return new self($collectorDirPath);
    }

    /**
     * @return TechnicalDebtInfoCollectorInterface[]
     */
    public function collect(): array
    {
        return $this->createTechnicalDebtInfoCollectors(...$this->getFilesInCollectorDirectory());
    }

    /**
     * @return \SplFileInfo[]
     */
    private function getFilesInCollectorDirectory(): array
    {
        return array_filter($this->listDirectory(), function (\SplFileInfo $item) {
            return $item->isFile();
        });
    }

    /**
     * @return \SplFileInfo[]
     */
    private function listDirectory(): array
    {
        return array_values(iterator_to_array(new \FilesystemIterator($this->collectorDirPath)));
    }

    /**
     * @param \SplFileInfo[] ...$files
     * @return TechnicalDebtInfoCollectorInterface[]
     */
    private function createTechnicalDebtInfoCollectors(\SplFileInfo ...$files): array
    {
        return array_map(function (\SplFileInfo $file) {
            return TechnicalDebtInfoCollector::fromFilePath($file->getRealPath());
        }, $files);
    }
}
