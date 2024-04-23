<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfTabs\Service\SfTabsService;
use Newageerp\SfReactTemplates\CoreTemplates\MainToolbar\MainToolbarTitle;
use Newageerp\SfReactTemplates\CoreTemplates\Popup\PopupWindow;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Newageerp\SfReactTemplates\Template\Placeholder;

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

            if ($isPopup) {
                $popupWindow = new PopupWindow();
                $popupWindow->getChildren()->addTemplate($listContent);
                $event->getPlaceholder()->addTemplate($popupWindow);
            } else {
                $event->getPlaceholder()->addTemplate($listContent);

                // $toolbarTitle = new MainToolbarTitle(
                //     $this->tabsUtilsV3->getTabToolbarTitle(
                //         $event->getData()['schema'],
                //         $event->getData()['type'],
                //     )
                // );
                // $event->getPlaceholder()->addTemplate($toolbarTitle);
            }
        }

        if ($event->isTemplateForAnyEntity('PageInlineList')) {
            $addToolbar = isset($event->getData()['addToolbar']) && $event->getData()['addToolbar'];

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
            );

            $listDataSource->getChildren()->addTemplate($listTable);

            $listContent->getChildren()->addTemplate($listDataSource);

            if ($isPopup) {
                $popupWindow = new PopupWindow();
                $popupWindow->setClassName('min-w-[75vw] max-w-[75vw]');
                $popupWindow->setTitle($this->entitiesUtilsV3->getTitlePluralBySlug($event->getData()['schema']));
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
