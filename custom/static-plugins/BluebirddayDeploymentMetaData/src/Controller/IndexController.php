<?php

namespace Bluebirdday\DeploymentMetaData\Controller;

use Bluebirdday\DeploymentMetaData\Service\DeploymentDataCollector;
use Bluebirdday\DeploymentMetaData\Service\PageLoader;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"storefront"})
 */
class IndexController extends StorefrontController
{
    private DeploymentDataCollector $dataCollector;
    private PageLoader $pageLoader;

    public function __construct(DeploymentDataCollector $dataCollector, PageLoader $pageLoader)
    {
        $this->dataCollector = $dataCollector;
        $this->pageLoader = $pageLoader;
    }

    /**
     * @return Response
     *
     * @Route("/deployment-info", name="frontend.bluebirdday.deployment_info", options={"seo"="false"}, methods={"GET"})
     */
    public function index(Request $request, SalesChannelContext $context): Response
    {
        $deploymentData = $this->dataCollector->getData();
        $page = $this->pageLoader->load($request, $context);

        return $this->renderStorefront(
          '@DeploymentMetaDataPlugin/storefront/page/index.html.twig',
            [
                'date' => $deploymentData->getDateTime(),
                'branch' => $deploymentData->getBranchName(),
                'page' => $page,
            ]
        );
    }
}
