<?php

namespace Bluebirdday\DeploymentMetaData\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

class CatalogInfo
{
    private EntityRepositoryInterface $productRepository;
    private EntityRepositoryInterface $categoryRepository;

    public function __construct(EntityRepositoryInterface $productRepository, EntityRepositoryInterface $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getCalatogInfo(): array
    {
        $context = Context::createDefaultContext();

        return [
            'productCount' => $this->productRepository->search(
                new Criteria([]),
                $context
            )->count(),
            'categoryCount' => $this->categoryRepository->search(
                new Criteria([]),
                $context
            )->count(),
        ];
    }
}
