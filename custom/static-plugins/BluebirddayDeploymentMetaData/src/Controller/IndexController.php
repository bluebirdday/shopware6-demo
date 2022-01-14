<?php

namespace Bluebirdday\DeploymentMetaData\Controller;

use Bluebirdday\DeploymentMetaData\Service\DeploymentDataCollector;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"storefront"})
 */
class IndexController extends StorefrontController
{
    private DeploymentDataCollector $dataCollector;

    public function __construct(DeploymentDataCollector $dataCollector)
    {
        $this->dataCollector = $dataCollector;
    }

    /**
     * @return Response
     *
     * @Route("/deployment-info", name="frontend.bluebirdday.deployment_info", options={"seo"="false"}, methods={"GET"})
     */
    public function index(): Response
    {
        $deploymentData = $this->dataCollector->getData();
        return $this->renderStorefront(
          '@DeploymentMetaDataPlugin/storefront/page/index.html.twig',
            [
                'date' => $deploymentData->getDateTime(),
                'branch' => $deploymentData->getBranchName(),
            ]
        );
    }
}
