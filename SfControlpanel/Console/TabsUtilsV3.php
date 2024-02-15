<?php

namespace Newageerp\SfControlpanel\Console;

use Newageerp\SfConfig\Service\ConfigService;
use Newageerp\SfControlpanel\Event\GetTabConfigEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TabsUtilsV3
{
    protected array $tabs = [];

    protected EntitiesUtilsV3 $entitiesUtilsV3;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        EntitiesUtilsV3 $entitiesUtilsV3,
        EventDispatcherInterface $eventDispatcher,
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
        $this->initTabs();
    }

    protected function initTabs()
    {
        $this->tabs = ConfigService::getConfig('tabs', true);
    }

    public function getTabToolbarTitle(string $schema, string $type): string
    {
        $schemaTitle = $this->entitiesUtilsV3->getTitlePluralBySlug($schema);

        $tab = $this->getTabBySchemaAndType($schema, $type);
        if ($tab && isset($tab['title']) && $tab['title']) {
            return $schemaTitle . ' - ' . $tab['title'];
        }
        return $schemaTitle;
    }

    public function getTabBySchemaAndType(string $schema, string $type): ?array
    {
        $tabsF = array_filter(
            $this->getTabs(),
            function ($item) use ($schema, $type) {
                return $item['config']['schema'] === $schema && $item['config']['type'] === $type;
            }
        );
        if (count($tabsF) > 0) {
            $tab =  reset($tabsF)['config'];

            $ev = new GetTabConfigEvent($tab);
            $this->getEventDispatcher()->dispatch($ev, GetTabConfigEvent::NAME);
            $tab = $ev->getTab();

            return $tab;
        }
        return null;
    }

    public function getTabQsFields(string $schema, string $type)
    {
        $tab = $this->getTabBySchemaAndType($schema, $type);
        if (!$tab) {
            return [];
        }
        if (isset($tab['quickSearchFilterKeys']) && $tab['quickSearchFilterKeys']) {
            return json_decode($tab['quickSearchFilterKeys'], true);
        }
        return $this->entitiesUtilsV3->getDefaultQsForSchema($schema);
    }

    public function getTabQuickFilters(string $schema, string $type)
    {
        $tab = $this->getTabBySchemaAndType($schema, $type);
        if (!$tab) {
            return [];
        }
        if (isset($tab['quickFilters']) && $tab['quickFilters']) {
            return $tab['quickFilters'];
        }
        return [];
    }

    public function getTabSort(string $schema, string $type)
    {
        $tab = $this->getTabBySchemaAndType($schema, $type);
        if (!$tab) {
            return [];
        }
        if (isset($tab['sort']) && $tab['sort']) {
            return json_decode($tab['sort'], true);
        }
        return $this->entitiesUtilsV3->getDefaultSortForSchema($schema);
    }

    public function getTabFilter(string $schema, string $type)
    {
        $tab = $this->getTabBySchemaAndType($schema, $type);
        if (!$tab) {
            return null;
        }
        if (isset($tab['predefinedFilter']) && $tab['predefinedFilter']) {
            return json_decode($tab['predefinedFilter'], true);
        }
        return null;
    }

    public function getTabTotals(string $schema, string $type)
    {
        $tab = $this->getTabBySchemaAndType($schema, $type);
        if (!$tab) {
            return [];
        }
        if (isset($tab['totals']) && $tab['totals']) {
            return $tab['totals'];
        }
        return [];
    }

    public function getTabsSwitchOptions(string $schema, string $type): array
    {
        $tab = $this->getTabBySchemaAndType($schema, $type);
        if (!$tab) {
            return [];
        }
        $otherTabs = [];
        if (isset($tab['tabGroup']) && $tab['tabGroup']) {
            $otherTabs =
                array_values(
                    array_map(
                        function ($t) {
                            return [
                                'value' => $t['config']['type'],
                                'label' => isset($t['config']['tabGroupTitle']) && $t['config']['tabGroupTitle'] ? $t['config']['tabGroupTitle'] : $t['config']['title']
                            ];
                        },
                        array_filter(
                            $this->getTabs(),
                            function ($t) use ($tab) {
                                return $t['config']['schema'] === $tab['schema'] && $t['config']['tabGroup'] === $tab['tabGroup'];
                            }
                        )
                    )
                );
        }
        return $otherTabs;
    }

    /**
     * Get the value of tabs
     *
     * @return array
     */
    public function getTabs(): array
    {
        return $this->tabs;
    }

    /**
     * Get the value of eventDispatcher
     *
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    /**
     * Set the value of eventDispatcher
     *
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return self
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): self
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }
}
