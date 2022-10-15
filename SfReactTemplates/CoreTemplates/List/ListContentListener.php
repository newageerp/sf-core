<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfAuth\Service\AuthService;
use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\TabsUtilsV3;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarDetailedSearch;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarExport;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarNewButton;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarQs;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarSort;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarTabSwitch;
use Newageerp\SfReactTemplates\CoreTemplates\MainToolbar\MainToolbarTitle;
use Newageerp\SfReactTemplates\CoreTemplates\Popup\PopupWindow;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ListContentListener implements EventSubscriberInterface
{
    protected EntitiesUtilsV3 $entitiesUtilsV3;

    protected TableHeaderService $tableHeaderService;

    protected TableRowService $tableRowService;

    protected TabsUtilsV3 $tabsUtilsV3;

    protected TableService $tableService;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        EntitiesUtilsV3 $entitiesUtilsV3,
        TableHeaderService $tableHeaderService,
        TableRowService $tableRowService,
        TabsUtilsV3 $tabsUtilsV3,
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
        if ($event->isTemplateForAnyEntity('PageMainList')) {
            $listContent = new ListContent(
                $event->getData()['schema'],
                $event->getData()['type'],
            );
            $isPopup = isset($event->getData()['isPopup']) && $event->getData()['isPopup'];

            $listDataSource = $this->getTableService()->buildListDataSourceWithToolbar(
                $event->getData()['schema'],
                $event->getData()['type'],
            );
            $listDataSource->setScrollToHeaderOnLoad(true);
            $listContent->getChildren()->addTemplate($listDataSource);

            $pageMainListToolbarMiddleContent = new LoadTemplateEvent($listDataSource->getToolbar()->getToolbarMiddle(), 'PageMainListToolbarMiddleContent', $event->getData());
            $this->eventDispatcher->dispatch($pageMainListToolbarMiddleContent, LoadTemplateEvent::NAME);
            
            $pageMainListToolbarLeftContent = new LoadTemplateEvent($listDataSource->getToolbar()->getToolbarLeft(), 'PageMainListToolbarLeftContent', $event->getData());
            $this->eventDispatcher->dispatch($pageMainListToolbarLeftContent, LoadTemplateEvent::NAME);

            $pageMainListToolbarRightContent = new LoadTemplateEvent($listDataSource->getToolbar()->getToolbarRight(), 'PageMainListToolbarRightContent', $event->getData());
            $this->eventDispatcher->dispatch($pageMainListToolbarRightContent, LoadTemplateEvent::NAME);
            

            if ($isPopup) {
                $popupWindow = new PopupWindow();
                $popupWindow->getChildren()->addTemplate($listContent);
                $event->getPlaceholder()->addTemplate($popupWindow);
            } else {
                $event->getPlaceholder()->addTemplate($listContent);

                $toolbarTitle = new MainToolbarTitle($this->entitiesUtilsV3->getTitlePluralBySlug($event->getData()['schema']));
                $event->getPlaceholder()->addTemplate($toolbarTitle);
            }
        }

        if ($event->isTemplateForAnyEntity('PageInlineList')) {
            $addToolbar = isset($event->getData()['addToolbar']) && $event->getData()['addToolbar'];

            $schema = $event->getData()['schema'];
            $type = $event->getData()['type'];

            $listContent = new ListContent(
                $event->getData()['schema'],
                $event->getData()['type'],
            );
            $isPopup = isset($event->getData()['isPopup']) && $event->getData()['isPopup'];

            $listDataSource = $this->getTableService()->buildListDataSource(
                $event->getData()['schema'],
                $event->getData()['type'],
            );
            if (isset($event->getData()['extraFilters']) && $event->getData()['extraFilters']) {
                $listDataSource->setExtraFilters(
                    $event->getData()['extraFilters']
                );
            }
            if ($addToolbar) {
                // toolbar
                $tab = $this->getTabsUtilsV3()->getTabBySchemaAndType(
                    $schema,
                    $type,
                );
                if ($tab) {
                    // QS
                    $qsFields = $this->getTabsUtilsV3()->getTabQsFields(
                        $schema,
                        $type,
                    );
                    if (count($qsFields) > 0) {
                        $listDataSource->getToolbar()->getToolbarLeft()->addTemplate(
                            new ToolbarQs($qsFields)
                        );
                    }

                    // SORT
                    $sort = $this->getTabsUtilsV3()->getTabSort(
                        $schema,
                        $type,
                    );
                    if (count($sort) > 0) {
                        $listDataSource->getToolbar()->getToolbarRight()->addTemplate(
                            new ToolbarSort($schema, $sort)
                        );
                    }

                    // DETAILED SEARCH
                    $listDataSource->getToolbar()->getToolbarRight()->addTemplate(
                        new ToolbarDetailedSearch($schema)
                    );
                }
            }
            $listTable = $this->getTableService()->buildTableData(
                $event->getData()['schema'],
                $event->getData()['type'],
                isset($event->getData()['addSelectButton']) && $event->getData()['addSelectButton']
            );

            $listDataSource->getChildren()->addTemplate($listTable);

            $listContent->getChildren()->addTemplate($listDataSource);

            if ($isPopup) {
                $popupWindow = new PopupWindow();
                $popupWindow->getChildren()->addTemplate($listContent);
                $event->getPlaceholder()->addTemplate($popupWindow);
            } else {
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
     * @return TabsUtilsV3
     */
    public function getTabsUtilsV3(): TabsUtilsV3
    {
        return $this->tabsUtilsV3;
    }

    /**
     * Set the value of tabsUtilsV3
     *
     * @param TabsUtilsV3 $tabsUtilsV3
     *
     * @return self
     */
    public function setTabsUtilsV3(TabsUtilsV3 $tabsUtilsV3): self
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
