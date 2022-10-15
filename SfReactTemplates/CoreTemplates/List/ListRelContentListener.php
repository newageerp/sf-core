<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfReactTemplates\CoreTemplates\Cards\WhiteCard;
use Newageerp\SfReactTemplates\CoreTemplates\MainToolbar\MainToolbarTitle;
use Newageerp\SfReactTemplates\CoreTemplates\Popup\PopupWindow;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ListRelContentListener implements EventSubscriberInterface
{
    protected TableService $tableService;

    public function __construct(
        TableService $tableService,
    ) {
        $this->tableService = $tableService;
    }

    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('ListRelContent')) {
            $wrap = $event->getData()['wrap'];

            $listDataSource = $this->getTableService()->buildListDataSourceForRel(
                $event->getData()['schema'],
                $event->getData()['type'],
                $event->getData()['rel'],
                $event->getData()['id'],
                $wrap,
            );
            
            $event->getPlaceholder()->addTemplate($listDataSource);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => 'onTemplate'
        ];
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
