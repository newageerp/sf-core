<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\EventListener;

use Newageerp\SfAuth\Service\AuthService;
use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfTabs\Service\SfTabsService;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarNewButton;
use Newageerp\SfReactTemplates\Event\ListCreatableEvent;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ToolbarLeftNewListener implements EventSubscriberInterface
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
        if ($event->isTemplateForAnyEntity('TableService.ToolbarLeft')) {
            $schema = $event->getData()['schema'];
            $type = $event->getData()['type'];
            if (isset($event->getData()['relElementId'])) {
                return;
            }

            $tab = $this->getTabsUtilsV3()->getTabBySchemaAndType(
                $schema,
                $type,
            );

            // CREATE BUTTON
            $disableCreate = isset($tab['disableCreate']) && $tab['disableCreate'];
            $isCreatableResult = $this->getEntitiesUtilsV3()->checkIsCreatable(
                $schema,
                AuthService::getInstance()->getUser()->getPermissionGroup(),
            );

            // ListCreatableEvent START
            $listCreatableEvent = new ListCreatableEvent(
                $schema,
                $isCreatableResult,
            );
            $this->eventDispatcher->dispatch($listCreatableEvent, ListCreatableEvent::NAME);
            $isCreatableResult = $listCreatableEvent->getIsCreatable();
            // ListCreatableEvent FINISH

            if (!$disableCreate && $isCreatableResult) {
                $event->getPlaceholder()->addTemplate(new ToolbarNewButton($schema));
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => [
                ['onTemplate', 1000]
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
