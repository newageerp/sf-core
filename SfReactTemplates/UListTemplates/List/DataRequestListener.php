<?php

namespace Newageerp\SfReactTemplates\UListTemplates\List;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newageerp\SfReactTemplates\AppTemplates\List\DataRequest\DataRequestEvent;
use Newageerp\SfTabs\Service\SfTabsService;
use Newageerp\SfUservice\Service\UService;

class DataRequestListener implements EventSubscriberInterface
{
    protected SfTabsService $tabsService;
    protected UService $uService;

    public function __construct(
        SfTabsService $tabsService,
        UService $uService,
    ) {
        $this->tabsService = $tabsService;
        $this->uService = $uService;
    }

    public function onDataRequest(DataRequestEvent $event)
    {
        $tab = $this->tabsService->getTabBySchemaAndType($event->getSchema(), $event->getType());
        if ($tab) {
            $uData = $this->uService->getListDataForSchema(
                $event->getSchema(),
                $event->getRequestPage(),
                $event->getRequestPageSize(),
                $event->getRequestFieldsToReturn(),
                $event->getRequestFilters(),
                $event->getRequestExtraData(),
                $event->getRequestSort(),
                $event->getRequestMetrics(),
            );
            $event->setResponseData($uData['data']);
            $event->setResponseMetrics($uData['totals']);
            $event->setResponseRecords($uData['records']);
            $event->setResponseCacheToken($uData['cacheRequest']);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            DataRequestEvent::NAME => 'onDataRequest'
        ];
    }
}
