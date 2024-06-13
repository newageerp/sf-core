<?php
namespace Newageerp\SfLogs;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Newageerp\SfFiles\Service\FilesHelperService;

/**
 * @Route(path="/app/nae-core/sf-logs")
 */
class SfLogsController extends OaBaseController {

    /**
     * @Route ("/list", methods={"GET"})
     */
    public function list(): JsonResponse
    {
        $list = FilesHelperService::scanDirFiles('/var/log');

        return $this->json(['success' => 1, 'data' => $list]);
    }

}