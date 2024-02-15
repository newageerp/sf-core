<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\EventListener;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfTabs\Service\SfTabsService;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarSort;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ToolbarRightSortListener implements EventSubscriberInterface
{

    protected EntitiesUtilsV3 $entitiesUtilsV3;

    protected SfTabsService $tabsUtilsV3;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        SfTabsService $tabsUtilsV3,
        EntitiesUtilsV3 $entitiesUtilsV3,
        EventDispatcherInterface $eventDispatcher,
    ) {
        $this->tabsUtilsV3 = $tabsUtilsV3;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('TableService.ToolbarRight')) {
            $schema = $event->getData()['schema'];
            $type = $event->getData()['type'];

            $sort = $this->getTabsUtilsV3()->getTabSort(
                $schema,
                $type,
            );
            if (count($sort) > 0) {
                $event->getPlaceholder()->addTemplate(
                    new ToolbarSort($schema, $sort)
                );
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => [
                ['onTemplate', 900]
            ]
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
}
