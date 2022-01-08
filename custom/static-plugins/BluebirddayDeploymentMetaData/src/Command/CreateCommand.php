<?php

namespace Bluebirdday\DeploymentMetaData\Command;

use Bluebirdday\DeploymentMetaData\Service\DeploymentDataCollector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends Command
{
    protected static $defaultName = 'deployment:metadata:create';

    private DeploymentDataCollector $dataCollector;

    public function __construct(DeploymentDataCollector $dataCollector)
    {
        parent::__construct();
        $this->dataCollector = $dataCollector;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $this->dataCollector->createData();
        $output->write(sprintf('Deployment meta data written to" %s"', $path));
        return Command::SUCCESS;
    }
}
