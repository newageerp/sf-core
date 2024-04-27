<?php

namespace Newageerp\SfExploreData;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newageerp\SfReactTemplates\AppTemplates\List\DataView\DataViewListDataSourceEvent;
use Newageerp\SfReactTemplates\AppTemplates\List\DataView\DataViewTabContainerEvent;
use Newageerp\SfReactTemplates\AppTemplates\List\DataView\DataViewDataTabEvent;
use Newageerp\SfTabs\Service\SfTabsService;
use Newageerp\SfUservice\Service\UService;
use Newageerp\SfReactTemplates\CoreTemplates\List\TableService;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Newageerp\SfReactTemplates\AppTemplates\List\DataRequest\DataRequestEvent;
use Newageerp\SfEntity\Repository\SfExploreDataItemRepository;

class SfExploreDataViewListener implements EventSubscriberInterface
{
    protected SfTabsService $tabsService;
    protected UService $uService;
    protected EventDispatcherInterface $eventDispatcher;
    protected TableService $tableService;
    protected EntityManagerInterface $em;
    protected SfExploreDataItemRepository $exploreItemRepo;

    public function __construct(
        SfTabsService $tabsService,
        UService $uService,
        EventDispatcherInterface $eventDispatcher,
        TableService $tableService,
        LoggerInterface $loggerInterface,
        EntityManagerInterface $em,
        SfExploreDataItemRepository $exploreItemRepo,
    ) {
        $this->tabsService = $tabsService;
        $this->uService = $uService;
        $this->eventDispatcher = $eventDispatcher;
        $this->tableService = $tableService;
        $this->em = $em;
        $this->exploreItemRepo = $exploreItemRepo;
    }

    public function onListDataSource(DataViewListDataSourceEvent $event)
    {
        if ($event->getSchema() === 'explore') {
            $event->getListDataSource()->setScrollToHeaderOnLoad(true);
        }
    }

    public function onTab(DataViewTabContainerEvent $event)
    {
       
    }

    public function onToolbarExtraTemplateEvent(LoadTemplateEvent $event)
    {
        
    }

    public function onDataTab(DataViewDataTabEvent $event)
    {
        if ($event->getSchema() === 'explore') {
            $item = $this->exploreItemRepo->findOneBy(['exploreId' => $event->getType()]);
            if ($item) {
                $listTable = $this->tableService->buildSimpleTable($item->getColumns());
                $event->getTabContainerItem()->getContent()->addTemplate($listTable);
            }
        }
    }

    public function onDataRequest(DataRequestEvent $event)
    {
        if ($event->getSchema() === 'explore') {
            $item = $this->exploreItemRepo->findOneBy(['exploreId' => $event->getType()]);

            if ($item) {
                $page = $event->getRequestPage();
                $pageSize = $event->getRequestPageSize();

                $sql = $item->getSqlData();
                $sql .= ' LIMIT ' . (($page - 1) * $pageSize) . ',' . $pageSize;

                $stmt = $this->em->getConnection()->prepare($item->getSqlCount());
                $resultCount = $stmt->executeQuery()->fetchFirstColumn();


                $stmt = $this->em->getConnection()->prepare($sql);
                $result = $stmt->executeQuery()->fetchAllAssociative();

                $event->setResponseData($result);
                // $event->setResponseMetrics($uData['totals']);
                $event->setResponseRecords($resultCount[0]);
                // $event->setResponseCacheToken($uData['cacheRequest']);
            }
        }
    }



    public static function getSubscribedEvents()
    {
        return [
            DataViewListDataSourceEvent::NAME => 'onListDataSource',
            DataViewTabContainerEvent::NAME => 'onTab',
            DataViewDataTabEvent::NAME => 'onDataTab',
            LoadTemplateEvent::NAME => 'onToolbarExtraTemplateEvent',
            DataRequestEvent::NAME => 'onDataRequest',
        ];
    }
}
