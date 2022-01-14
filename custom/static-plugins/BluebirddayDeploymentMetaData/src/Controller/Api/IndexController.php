<?php

namespace Bluebirdday\DeploymentMetaData\Controller\Api;

use Bluebirdday\DeploymentMetaData\Service\DeploymentDataCollector;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"storefront"})
 */
class IndexController extends AbstractController
{
    private DeploymentDataCollector $dataCollector;

    public function __construct(DeploymentDataCollector $dataCollector)
    {
        $this->dataCollector = $dataCollector;
    }

    /**
     * @return JsonResponse
     *
     * @Route(
     *     "/deployment-info",
     *      name="frontend.bluebirdday.api.deployment_info",
     *     options={"seo"="false"},
     *     methods={"GET"},
     *     defaults={"XmlHttpRequest"=true}
     * )
     */
    public function index(): JsonResponse
    {
        $deploymentData = $this->dataCollector->getData();
        return new JsonResponse([
            'date' => $deploymentData->getDateTime(),
            'branch' => $deploymentData->getBranchName(),
        ]);
    }
}
