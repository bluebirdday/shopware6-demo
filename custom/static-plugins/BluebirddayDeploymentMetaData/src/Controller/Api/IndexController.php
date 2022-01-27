<?php

namespace Bluebirdday\DeploymentMetaData\Controller\Api;

use Bluebirdday\DeploymentMetaData\Route\DeploymentInfoResponse;
use Bluebirdday\DeploymentMetaData\Service\DeploymentDataCollector;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\StoreApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @RouteScope(scopes={"store-api"})
 */
class IndexController extends AbstractController
{
    private DeploymentDataCollector $dataCollector;

    public function __construct(DeploymentDataCollector $dataCollector)
    {
        $this->dataCollector = $dataCollector;
    }

    /**
     * @return StoreApiResponse
     *
     * @see https://shopware.local/store-api/_info/swagger.html#/
     * @see  curl -H "sw-access-key: MYACESSKEY" https://shopware.local/store-api/deployment-info
     *
     * @OA\Get(
     *     path="/deployment-info",
     *     description="Get the latest deployment info",
     *     operationId="deploymentInfo",
     *     tags={"Store API", "Deployment meta data"},
     *     @OA\Response (
     *         response="200"
     *     )
     * )
     * @Route(
     *     "/store-api/deployment-info",
     *      name="frontend.bluebirdday.api.deployment_info",
     *     options={"seo"="false"},
     *     methods={"GET"},
     *     defaults={"XmlHttpRequest"=true}
     * )
     */
    public function index(): StoreApiResponse
    {
        $deploymentData = $this->dataCollector->getData();
        return new DeploymentInfoResponse($deploymentData);
    }
}
