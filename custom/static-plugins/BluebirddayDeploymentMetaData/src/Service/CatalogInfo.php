<?php

namespace Bluebirdday\DeploymentMetaData\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class CatalogInfo
{
    private EntityRepositoryInterface $productRepository;
    private EntityRepositoryInterface $categoryRepository;

    public function __construct(EntityRepositoryInterface $productRepository, EntityRepositoryInterface $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getCatalogInfo(): array
    {
        $context = Context::createDefaultContext();
        $criteria = (new Criteria())->addFilter(new EqualsFilter('active', true));

        $products = $this->productRepository->search($criteria, $context);
        $categories = $this->categoryRepository->search($criteria, $context);

        return [
            'productCount' => $products->count(),
            'categoryCount' => $categories->count(),
        ];
    }
}
