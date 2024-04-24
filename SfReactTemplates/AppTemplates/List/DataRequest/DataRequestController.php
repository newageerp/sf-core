<?php

namespace Newageerp\SfReactTemplates\AppTemplates\List\DataRequest;

use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Newageerp\SfAuth\Service\AuthService;

/**
 * @Route(path="/app/data-request")
 */
class DataRequestController extends OaBaseController
{
    /**
     * @Route(path="/get/{schema}/{type}", methods={"POST"})
     */
    public function getData(Request $request) {
        $request = $this->transformJsonBody($request);

        $user = $this->findUser($request);
        if (!$user) {
            throw new \Exception('Invalid user');
        }
        AuthService::getInstance()->setUser($user);

        $schema = $request->get('schema');
        $type = $request->get('type');

        $page = $request->get('page') ? $request->get('page') : 1;
        $pageSize = $request->get('pageSize') ? $request->get('pageSize') : 20;
        $fieldsToReturn = $request->get('fieldsToReturn') ? $request->get('fieldsToReturn') : ['id'];
        $filters = $request->get('filters') ? $request->get('filters') : [];
        $extraData = $request->get('extraData') ? $request->get('extraData') : [];
        $sort = $request->get('sort') ? $request->get('sort') : [];
        $totals = $request->get('totals') ? $request->get('totals') : [];

        // TODO EVENTS FOR REQUEST PARAMS


        // TODO EVENTS FOR DATA
        $dataRequestEvent = new DataRequestEvent($schema, $type);
        $dataRequestEvent->setRequestPage($page);
        $dataRequestEvent->setRequestPageSize($pageSize);
        $dataRequestEvent->setRequestFieldsToReturn($fieldsToReturn);
        $dataRequestEvent->setRequestFilters($filters);
        $dataRequestEvent->setRequestExtraData($extraData);
        $dataRequestEvent->setRequestSort($sort);
        $dataRequestEvent->setRequestMetrics($totals);
        $this->eventDispatcher->dispatch($dataRequestEvent, DataRequestEvent::NAME);

        return $this->json([
            'metrics' => $dataRequestEvent->getResponseMetrics(),
            'records' => $dataRequestEvent->getResponseRecords(),
            'data' => $dataRequestEvent->getResponseData(),
            'cacheToken' => $dataRequestEvent->getResponseCacheToken(),
        ]);
    }
}
