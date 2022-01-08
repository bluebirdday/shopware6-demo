<?php

namespace Bluebirdday\DeploymentMetaData\Command;

use Bluebirdday\DeploymentMetaData\Service\DeploymentDataCollector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowCommand extends Command
{
    protected static $defaultName = 'deployment:metadata:show';
    private DeploymentDataCollector $dataCollector;

    public function __construct(DeploymentDataCollector $dataCollector)
    {
        parent::__construct();
        $this->dataCollector = $dataCollector;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = $this->dataCollector->getData();
        $output->writeln('Branch: ' . $data->getBranchName());
        $output->writeln('Date: ' . $data->getDateTime()->format('Y-m-d H:i:s') . ' ' . $data->getDateTime()->getTimezone()->getName());
        return Command::SUCCESS;
    }
}
