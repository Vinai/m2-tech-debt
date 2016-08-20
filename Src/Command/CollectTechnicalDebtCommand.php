<?php

declare(strict_types = 1);

namespace VinaiKopp\TechnicalDebtAggregate\Src\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use VinaiKopp\TechnicalDebtAggregate\Src\TechnicalDebtInfo;

class CollectTechnicalDebtCommand extends Command
{
    /**
     * @var TechnicalDebtInfo
     */
    private $technicalDebtInfo;

    public function __construct(TechnicalDebtInfo $technicalDebtInfo)
    {
        parent::__construct();
        $this->technicalDebtInfo = $technicalDebtInfo;
    }
    
    protected function configure()
    {
        $this->setName('dev:technical-debt:collect');
        $this->setDescription('Collect module technical debt info in CSV format');
        $this->addArgument('file', InputArgument::OPTIONAL, 'File to write the CSV stream to', 'STDOUT');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        if ('STDOUT' === $file) {
            $output->write($this->technicalDebtInfo->asString(), false, OutputInterface::OUTPUT_RAW);
        } else {
            $this->technicalDebtInfo->toFile($file);
            $output->writeln(sprintf('<info>CSV written to file "%s"</info>', $file));
        }
    }
}
