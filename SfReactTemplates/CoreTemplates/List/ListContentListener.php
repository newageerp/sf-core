<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfTabs\Service\SfTabsService;
use Newageerp\SfReactTemplates\CoreTemplates\Popup\PopupWindow;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\CoreTemplates\Tabs\TabContainer;
use Newageerp\SfReactTemplates\CoreTemplates\Tabs\TabContainerItem;

class ListContentListener implements EventSubscriberInterface
{
    protected EntitiesUtilsV3 $entitiesUtilsV3;

    protected TableHeaderService $tableHeaderService;

    protected TableRowService $tableRowService;

    protected SfTabsService $tabsUtilsV3;

    protected TableService $tableService;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        EntitiesUtilsV3 $entitiesUtilsV3,
        TableHeaderService $tableHeaderService,
        TableRowService $tableRowService,
        SfTabsService $tabsUtilsV3,
        TableService $tableService,
        EventDispatcherInterface $eventDispatcher,
    ) {
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
        $this->tableHeaderService = $tableHeaderService;
        $this->tableRowService = $tableRowService;
        $this->tabsUtilsV3 = $tabsUtilsV3;
        $this->tableService = $tableService;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('PageMainListXX')) {
            $listContent = new ListContent(); // OK
            $isPopup = isset($event->getData()['isPopup']) && $event->getData()['isPopup'];

            // $listDataSource = $this->getTableService()->buildListDataSourceWithToolbar(
            //     $event->getData()['schema'],
            //     $event->getData()['type'],
            // );
            $schema = $event->getData()['schema'];
            $type = $event->getData()['type'];
            
            $tab = $this->getTabsUtilsV3()->getTabBySchemaAndType(
                $schema,
                $type,
            );
            if ($tab) {
                $listDataSource = $this->getTableService()->buildListDataSource(
                    $schema,
                    $type,
                ); // OK

                $listTable = $this->getTableService()->buildTableData(
                    $schema,
                    $type,
                ); // OK

                $totals = $this->tabsUtilsV3->getTabTotals($schema, $type);// OK

                if (isset($tab['summary']) && $tab['summary']) {
                    $tabContainer = new TabContainer(); // OK

                    $tabContainerItem = new TabContainerItem('Data'); // OK
                    $tabContainer->addItem($tabContainerItem); // OK
                    $tabContainerItem->getContent()->addTemplate($listTable);// OK
                    if ($totals) {
                        $listTotals = new ListDataTotals($totals);// OK
                        $tabContainerItem->getContent()->addTemplate($listTotals);// OK
                    }

                    $tabContainerItem = new TabContainerItem('Summary'); // OK
                    $tabContainer->addItem($tabContainerItem);// OK

                    $listDataSummary = new ListDataSummary($schema, $tab['summary']);// OK
                    $tabContainerItem->getContent()->addTemplate($listDataSummary);// OK

                    $listDataSource->getChildren()->addTemplate($tabContainer); // OK
                } else { // OK
                    $listDataSource->getChildren()->addTemplate($listTable);// OK

                    if ($totals) {// OK
                        $listTotals = new ListDataTotals($totals);// OK
                        $listDataSource->getChildren()->addTemplate($listTotals);// OK
                    }
                }

                $templatesEventData = [
                    'schema' => $schema,
                    'type' => $type,
                    'listDataSource' => $listDataSource,
                ];// OK

                // TEMPLATES CALL
                $templateEvent = new LoadTemplateEvent(
                    new Placeholder(),
                    'TableService.Toolbar',
                    $templatesEventData
                );
                $this->eventDispatcher->dispatch($templateEvent, LoadTemplateEvent::NAME); // OK

                $pageMainListToolbarMiddleContent = new LoadTemplateEvent($listDataSource->getToolbar()->getToolbarMiddle(), 'PageMainListToolbarMiddleContent', $templatesEventData); // OK
                $this->eventDispatcher->dispatch($pageMainListToolbarMiddleContent, LoadTemplateEvent::NAME); // OK

                $pageMainListToolbarLeftContent = new LoadTemplateEvent($listDataSource->getToolbar()->getToolbarLeft(), 'PageMainListToolbarLeftContent', $templatesEventData); // OK
                $this->eventDispatcher->dispatch($pageMainListToolbarLeftContent, LoadTemplateEvent::NAME); // OK

                $pageMainListToolbarRightContent = new LoadTemplateEvent($listDataSource->getToolbar()->getToolbarRight(), 'PageMainListToolbarRightContent', $templatesEventData); // OK
                $this->eventDispatcher->dispatch($pageMainListToolbarRightContent, LoadTemplateEvent::NAME); // OK
            }
            $listDataSource->setScrollToHeaderOnLoad(true);


            $listContent->getChildren()->addTemplate($listDataSource);// OK

            if ($isPopup) { // OK
                $popupWindow = new PopupWindow();
                $popupWindow->getChildren()->addTemplate($listContent);
                $event->getPlaceholder()->addTemplate($popupWindow);
            } else { // OK
                $event->getPlaceholder()->addTemplate($listContent); // OK
            }
        }

        if ($event->isTemplateForAnyEntity('PageInlineListXX')) {
            $addToolbar = isset($event->getData()['addToolbar']) && $event->getData()['addToolbar']; // OK

            $listContent = new ListContent(); // OK
            $isPopup = isset($event->getData()['isPopup']) && $event->getData()['isPopup']; // OK

            $listDataSource = $this->getTableService()->buildListDataSource(
                $event->getData()['schema'],
                $event->getData()['type'],
            ); // OK

            if (isset($event->getData()['extraFilters']) && $event->getData()['extraFilters']) {
                $listDataSource->setExtraFilters(
                    $event->getData()['extraFilters']
                );
            } // OK
            if ($addToolbar) { // OK
                $templatesEventData = [
                    'listDataSource' => $listDataSource,
                    ...$event->getData(),
                ];

                $templateEvent = new LoadTemplateEvent(
                    new Placeholder(),
                    'TableService.Toolbar',
                    $templatesEventData
                );
                $this->eventDispatcher->dispatch($templateEvent, LoadTemplateEvent::NAME);
            }

            $listTable = $this->getTableService()->buildTableData(
                $event->getData()['schema'],
                $event->getData()['type'],
                isset($event->getData()['addSelectButton']) && $event->getData()['addSelectButton']
            ); // OK

            $listDataSource->getChildren()->addTemplate($listTable); // OK

            $listContent->getChildren()->addTemplate($listDataSource); // OK

            if ($isPopup) { // OK
                $popupWindow = new PopupWindow();
                $popupWindow->setClassName('min-w-[75vw] max-w-[75vw]');
                $popupWindow->setTitle($this->entitiesUtilsV3->getTitlePluralBySlug($event->getData()['schema']));
                $popupWindow->getChildren()->addTemplate($listContent);
                $event->getPlaceholder()->addTemplate($popupWindow);
            } else { // OK
                $event->getPlaceholder()->addTemplate($listContent);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => 'onTemplate'
        ];
    }

    /**
     * Get the value of tabsUtilsV3
     *
     * @return SfTabsService
     */
    public function getTabsUtilsV3(): SfTabsService
    {
        return $this->tabsUtilsV3;
    }

    /**
     * Set the value of tabsUtilsV3
     *
     * @param SfTabsService $tabsUtilsV3
     *
     * @return self
     */
    public function setTabsUtilsV3(SfTabsService $tabsUtilsV3): self
    {
        $this->tabsUtilsV3 = $tabsUtilsV3;

        return $this;
    }

    /**
     * Get the value of entitiesUtilsV3
     *
     * @return EntitiesUtilsV3
     */
    public function getEntitiesUtilsV3(): EntitiesUtilsV3
    {
        return $this->entitiesUtilsV3;
    }

    /**
     * Set the value of entitiesUtilsV3
     *
     * @param EntitiesUtilsV3 $entitiesUtilsV3
     *
     * @return self
     */
    public function setEntitiesUtilsV3(EntitiesUtilsV3 $entitiesUtilsV3): self
    {
        $this->entitiesUtilsV3 = $entitiesUtilsV3;

        return $this;
    }

    /**
     * Get the value of tableService
     *
     * @return TableService
     */
    public function getTableService(): TableService
    {
        return $this->tableService;
    }

    /**
     * Set the value of tableService
     *
     * @param TableService $tableService
     *
     * @return self
     */
    public function setTableService(TableService $tableService): self
    {
        $this->tableService = $tableService;

        return $this;
    }
}
