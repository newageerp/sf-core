<?php

namespace Newageerp\SfReactTemplates\AppTemplates\List\DataView;

use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Newageerp\SfReactTemplates\CoreTemplates\List\ListContent;
use Newageerp\SfReactTemplates\CoreTemplates\List\ListDataSource;
use Newageerp\SfReactTemplates\CoreTemplates\Tabs\TabContainer;
use Newageerp\SfReactTemplates\CoreTemplates\Tabs\TabContainerItem;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\CoreTemplates\Popup\PopupWindow;
use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;

class DataViewListener implements EventSubscriberInterface
{
    protected EventDispatcherInterface $eventDispatcher;
    protected EntitiesUtilsV3 $entitiesUtilsV3;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EntitiesUtilsV3 $entitiesUtilsV3,
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
    }


    public function onTemplate(LoadTemplateEvent $event)
    {
        if (
            $event->isTemplateForAnyEntity('App-List-DataView') ||
            $event->isTemplateForAnyEntity('PageInlineList') ||
            $event->isTemplateForAnyEntity('PageMainList')
        ) {
            $schema = $event->getData()['schema'];
            $type = $event->getData()['type'];

            $listContent = new ListContent();

            // LIST DATA SOURCE
            $listDataSource = new ListDataSource($schema, $type);
            if (isset($event->getData()['scrollToHead']) && $event->getData()['scrollToHead']) {
                $listDataSource->setScrollToHeaderOnLoad(true);
            }

            $listContent->getChildren()->addTemplate($listDataSource);

            if (isset($event->getData()['extraFilters']) && $event->getData()['extraFilters']) {
                $listDataSource->setExtraFilters(
                    $event->getData()['extraFilters']
                );
            }

            // LIST DATA SOURCE EVENT
            $dataViewListDataSourceEvent = new DataViewListDataSourceEvent(
                $schema,
                $type,
                $listDataSource
            );
            $this->eventDispatcher->dispatch($dataViewListDataSourceEvent, DataViewListDataSourceEvent::NAME);

            // LIST DATA SOURCE TOOLBAR
            if (isset($event->getData()['addToolbar']) && $event->getData()['addToolbar']) {
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

                // LIST DATA SOURCE EXTRA TOOLBAR
                if (isset($event->getData()['toolbarTemplateEvents'])) {
                    foreach ($event->getData()['toolbarTemplateEvents'] as $tlbrEvent) {
                        $templateEvent = new LoadTemplateEvent(
                            new Placeholder(),
                            $tlbrEvent,
                            $templatesEventData
                        );
                        $this->eventDispatcher->dispatch($templateEvent, LoadTemplateEvent::NAME);
                    }
                }
            }


            // TABS
            $tabContainer = new TabContainer();
            $listDataSource->getChildren()->addTemplate($tabContainer);

            $tabContainerItem = new TabContainerItem('Data');
            $tabContainer->addItem($tabContainerItem);

            // DATA TAB CONTENT
            $dataViewDataTabEvent = new DataViewDataTabEvent(
                $schema,
                $type,
                $event->getData(),
                $tabContainerItem
            );
            $this->eventDispatcher->dispatch($dataViewDataTabEvent, DataViewDataTabEvent::NAME);

            // TAB CONTAINER EVENT
            $dataViewTabContainerEvent = new DataViewTabContainerEvent(
                $schema,
                $type,
                $tabContainer
            );
            $this->eventDispatcher->dispatch($dataViewTabContainerEvent, DataViewTabContainerEvent::NAME);

            // TODO CONTENT AFTER TABS


            $isPopup = isset($event->getData()['isPopup']) && $event->getData()['isPopup'];
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
}
