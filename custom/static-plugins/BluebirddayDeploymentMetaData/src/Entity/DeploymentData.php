<?php

namespace Bluebirdday\DeploymentMetaData\Entity;

class DeploymentData
{
    private string $shopName;

    private \DateTime $dateTime;

    private string $branchName;

    /**
     * @return string
     */
    public function getShopName(): string
    {
        return $this->shopName;
    }

    /**
     * @param string $shopName
     */
    public function setShopName(string $shopName): void
    {
        $this->shopName = $shopName;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    /**
     * @param \DateTime $dateTime
     */
    public function setDateTime(\DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @return string
     */
    public function getBranchName(): string
    {
        return $this->branchName;
    }

    /**
     * @param string $branchName
     */
    public function setBranchName(string $branchName): void
    {
        $this->branchName = $branchName;
    }

    public function toJson()
    {
        return json_encode(
          [
              'branch' => $this->branchName,
              'date' => $this->dateTime->format('Y-m-d H:i:s'),
          ]
        );
    }
}
