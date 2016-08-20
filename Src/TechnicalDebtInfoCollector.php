<?php

declare(strict_types = 1);

namespace VinaiKopp\TechnicalDebtAggregate\Src;

use VinaiKopp\TechnicalDebtAggregate\Src\Exception\InvalidCollectorScriptException;

class TechnicalDebtInfoCollector implements TechnicalDebtInfoCollectorInterface
{
    /**
     * @var string
     */
    private $collectorScript;

    /**
     * @var string
     */
    private $args;

    private function __construct(string $collectorScript, string $args)
    {
        $this->validateCollectorExists($collectorScript);
        $this->validateCollectorIsExecutable($collectorScript);
        $this->collectorScript = $collectorScript;
        $this->args = $args;
    }

    private function validateCollectorExists(string $collectorScript)
    {
        if (! file_exists($collectorScript)) {
            $message = sprintf('Technical debt collector "%s" is not found', $collectorScript);
            throw new InvalidCollectorScriptException($message);
        }
    }

    private function validateCollectorIsExecutable(string $collectorScript)
    {
        if (! is_executable($collectorScript)) {
            $message = sprintf('The technical debt collector "%s" is not executable', basename($collectorScript));
            throw new InvalidCollectorScriptException($message);
        }
    }

    public static function fromFilePath(string $collectorScript): self
    {
        return new self($collectorScript, '');
    }

    private function withArgs(string $arg): self
    {
        return new self($this->collectorScript, $arg);
    }

    public function collectForModule(string $moduleDir): string
    {
        return $this->withArgs(escapeshellarg($moduleDir))->execute();
    }

    public function name(): string
    {
        return $this->withArgs('--name')->execute();
    }

    private function execute(): string
    {
        exec($this->command(), $output);
        return trim(implode(PHP_EOL, $output));
    }

    public function command(): string
    {
        return trim($this->collectorScript . ' ' . $this->args);
    }
}
