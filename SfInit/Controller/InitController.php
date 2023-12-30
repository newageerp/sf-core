<?php

namespace Newageerp\SfInit\Controller;

use Newageerp\SfAuth\Service\AuthService;
use Newageerp\SfStatus\Service\StatusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(path="/app/nae-core/init")
 */
class InitController extends AbstractController
{

    /**
     * @Route ("/", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function init(AuthService $authService, StatusService $statusService): JsonResponse
    {
        $data = [];
        $data['loginUrl'] = $authService->getFrontEndUrl();

        return $this->json([
            'data' => $data,
            'status' => $statusService->getStatuses(),
        ]);
    }
}
