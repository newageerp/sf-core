<?php

namespace Newageerp\SfReactTemplates\UListTemplates\List;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newageerp\SfReactTemplates\AppTemplates\List\DataView\DataViewListDataSourceEvent;
use Newageerp\SfReactTemplates\AppTemplates\List\DataView\DataViewTabContainerEvent;
use Newageerp\SfReactTemplates\AppTemplates\List\DataView\DataViewDataTabEvent;
use Newageerp\SfReactTemplates\CoreTemplates\CustomPluginTemplate;
use Newageerp\SfTabs\Service\SfTabsService;
use Newageerp\SfUservice\Service\UService;
use Newageerp\SfReactTemplates\CoreTemplates\Tabs\TabContainerItem;
use Newageerp\SfReactTemplates\CoreTemplates\List\ListDataSummary;
use Newageerp\SfReactTemplates\CoreTemplates\List\ListDataTotals;
use Newageerp\SfReactTemplates\CoreTemplates\List\TableService;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DataViewListener implements EventSubscriberInterface
{
    protected SfTabsService $tabsService;
    protected UService $uService;
    protected EventDispatcherInterface $eventDispatcher;
    protected TableService $tableService;

    public function __construct(
        SfTabsService $tabsService,
        UService $uService,
        EventDispatcherInterface $eventDispatcher,
        TableService $tableService,
    ) {
        $this->tabsService = $tabsService;
        $this->uService = $uService;
        $this->eventDispatcher = $eventDispatcher;
        $this->tableService = $tableService;
    }

    public function onListDataSource(DataViewListDataSourceEvent $event)
    {
        $tab = $this->tabsService->getTabBySchemaAndType($event->getSchema(), $event->getType());
        if ($tab) {
            $event->getListDataSource()->setSort(
                $this->tabsService->getTabSort($event->getSchema(), $event->getType())
            );
            if (isset($tab['pageSize']) && $tab['pageSize']) {
                $event->getListDataSource()->setPageSize($tab['pageSize']);
            }
            if (isset($tab['hideWithoutFilter']) && $tab['hideWithoutFilter']) {
                $event->getListDataSource()->setHideWithoutFilter($tab['hideWithoutFilter']);
            }

            $filters = $event->getListDataSource()->getExtraFilters();
            if ($fs = $this->tabsService->getTabFilter($event->getSchema(), $event->getType())) {
                $filters[] = $fs;
            }
            $event->getListDataSource()->setExtraFilters($filters);
        }
    }

    public function onTab(DataViewTabContainerEvent $event)
    {
        $tab = $this->tabsService->getTabBySchemaAndType($event->getSchema(), $event->getType());
        if ($tab) {
            if (isset($tab['summary']) && $tab['summary']) {
                $tabContainerItem = new TabContainerItem('Summary');
                $event->getTabContainer()->addItem($tabContainerItem);

                $listDataSummary = new ListDataSummary($event->getSchema(), $tab['summary']);
                $tabContainerItem->getContent()->addTemplate($listDataSummary);
            }

            if (isset($tab['tab-charts'])) {
                foreach ($tab['tab-charts'] as $tabChart) {
                    $tabContainerItem = new TabContainerItem($tabChart['title']);
                    $event->getTabContainer()->addItem($tabContainerItem);

                    $chartEl = new CustomPluginTemplate(
                        '_.AppBundle.ListDataChart',
                        [
                            'id' => $tabChart['id'],
                            'charts' => $tabChart['charts'],
                        ]
                    );
                    $tabContainerItem->getContent()->addTemplate($chartEl);
                }
            }
        }
    }

    public function onToolbarExtraTemplateEvent(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('PageMainListToolbar')) {
            $listDataSource = $event->getData()['listDataSource'];
            $templatesEventData = $event->getData();

            $pageMainListToolbarMiddleContent = new LoadTemplateEvent($listDataSource->getToolbar()->getToolbarMiddle(), 'PageMainListToolbarMiddleContent', $templatesEventData);
            $this->eventDispatcher->dispatch($pageMainListToolbarMiddleContent, LoadTemplateEvent::NAME);

            $pageMainListToolbarLeftContent = new LoadTemplateEvent($listDataSource->getToolbar()->getToolbarLeft(), 'PageMainListToolbarLeftContent', $templatesEventData);
            $this->eventDispatcher->dispatch($pageMainListToolbarLeftContent, LoadTemplateEvent::NAME);

            $pageMainListToolbarRightContent = new LoadTemplateEvent($listDataSource->getToolbar()->getToolbarRight(), 'PageMainListToolbarRightContent', $templatesEventData);
            $this->eventDispatcher->dispatch($pageMainListToolbarRightContent, LoadTemplateEvent::NAME);
        }
    }

    public function onDataTab(DataViewDataTabEvent $event)
    {
        $tab = $this->tabsService->getTabBySchemaAndType($event->getSchema(), $event->getType());
        if ($tab) {
            $addSelectButton = isset($event->getEventData()['addSelectButton']) && $event->getEventData()['addSelectButton'];

            $listTable = $this->tableService->buildTableData($event->getSchema(), $event->getType(), $addSelectButton);
            $event->getTabContainerItem()->getContent()->addTemplate($listTable);

            $totals = $this->tabsService->getTabTotals($event->getSchema(), $event->getType());
            if ($totals) {
                $listTotals = new ListDataTotals($totals);
                $event->getTabContainerItem()->getContent()->addTemplate($listTotals);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            DataViewListDataSourceEvent::NAME => 'onListDataSource',
            DataViewTabContainerEvent::NAME => 'onTab',
            DataViewDataTabEvent::NAME => 'onDataTab',
            LoadTemplateEvent::NAME => 'onToolbarExtraTemplateEvent'
        ];
    }
}
