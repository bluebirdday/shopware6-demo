<?php

namespace Bluebirdday\DeploymentMetaData\Command;

use Bluebirdday\DeploymentMetaData\Service\CatalogInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CatalogInfoCommand extends Command
{
    protected static $defaultName = 'deployment:info:catalog';

    private CatalogInfo $catalogInfo;

    public function __construct(CatalogInfo $catalogInfo)
    {
        parent::__construct();
        $this->catalogInfo = $catalogInfo;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->catalogInfo->getCalatogInfo();
        $output->writeln('Current catalog info:');
        $output->write(print_r($data, true));
        return Command::SUCCESS;
    }
}
