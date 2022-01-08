<?php

namespace Bluebirdday\DeploymentMetaData\Service;

use Bluebirdday\DeploymentMetaData\Entity\DeploymentData;
use http\Exception\RuntimeException;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Filesystem\Filesystem;

class DeploymentDataCollector
{
    private string $file = 'deployment-data.json';

    private SystemConfigService $systemConfigService;
    private Filesystem $filesystem;

    public function __construct(SystemConfigService $systemConfigService, Filesystem $filesystem)
    {
        $this->systemConfigService = $systemConfigService;
        $this->filesystem = $filesystem;
    }

    public function getData(): DeploymentData
    {
        $file = $this->file;
        if (!$this->filesystem->exists($file)) {
            throw new \RuntimeException(sprintf('File "%s" could not be opened for reading'), $file);
        }
        $fileData = file_get_contents($this->file);
        $fileData = json_decode($fileData);

        if (!$fileData) {
            throw new RuntimeException(sprintf('File %s does not contain valid json', $file));
        }
        $data = new DeploymentData();
        $data->setBranchName($fileData->branch ?? null);
        $data->setShopName($this->systemConfigService->get('DeploymentMetaDataPlugin.config.projectName'));
        if ($fileData->date) {
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $fileData->date);
            if (!$date) {
                throw new \RuntimeException(sprintf('Invalid date provided: "%s"', $fileData->date));
            }
            $data->setDateTime($date);
        }
        return $data;
    }

    public function createData(string $banchName = 'master', \DateTime $dateTime = null): string
    {
        $data = new DeploymentData();
        $data->setBranchName($banchName);
        if (!$dateTime) {
            $dateTime = new \DateTime();
        }
        $data->setDateTime($dateTime);
        return $this->writeData($data);
    }

    public function writeData(DeploymentData $deploymentData): string
    {
        $path = $this->file;
        $this->filesystem->dumpFile(
            $path,
            $deploymentData->toJson()
        );

        return $path;
    }
}
