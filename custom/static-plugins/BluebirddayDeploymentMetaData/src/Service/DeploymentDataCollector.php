<?php

namespace Bluebirdday\DeploymentMetaData\Service;

use Bluebirdday\DeploymentMetaData\Entity\DeploymentData;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Filesystem\Filesystem;

class DeploymentDataCollector
{
    private SystemConfigService $systemConfigService;
    private Filesystem $filesystem;

    public function __construct(SystemConfigService $systemConfigService, Filesystem $filesystem)
    {
        $this->systemConfigService = $systemConfigService;
        $this->filesystem = $filesystem;
    }

    public function getData(): DeploymentData
    {
        $data = new DeploymentData();
        $data->setBranchName('master');
        $data->setShopName($this->systemConfigService->get('DeploymentMetaDataPlugin.config.projectName'));
        $data->setDateTime(new \DateTime());
        return $data;
    }

    public function createData(string $banchName = 'master', \DateTime $dateTime = null): void
    {
        $data = new DeploymentData();
        $data->setBranchName($banchName);
        if (!$dateTime) {
            $dateTime = new \DateTime();
        }
        $data->setDateTime($dateTime);
        $this->writeData($data);
    }

    public function writeData(DeploymentData $deploymentData): void
    {
        $this->filesystem->dumpFile(
            'deployment-data.json',
            $deploymentData->toJson()
        );
    }
}
