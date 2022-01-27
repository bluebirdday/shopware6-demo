<?php

namespace Bluebirdday\DeploymentMetaData\Controller\Api;

use Bluebirdday\DeploymentMetaData\Service\DeploymentDataCollector;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @return JsonResponse
     *
     * @see https://shopware.local/store-api/_info/swagger.html#/
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
    public function index(): JsonResponse
    {
        $deploymentData = $this->dataCollector->getData();
        return new JsonResponse([
            'date' => $deploymentData->getDateTime(),
            'branch' => $deploymentData->getBranchName(),
        ]);
    }
}
