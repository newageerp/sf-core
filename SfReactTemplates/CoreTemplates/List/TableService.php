<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfAuth\Service\AuthService;
use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfControlpanel\Console\TabsUtilsV3;
use Newageerp\SfReactTemplates\CoreTemplates\Cards\WhiteCard;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarBookmark;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarDetailedSearch;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarExport;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarNewButton;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarQs;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarQuickFilters;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarSort;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarTabSwitch;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

use Newageerp\SfReactTemplates\CoreTemplates\Tabs\TabContainer;
use Newageerp\SfReactTemplates\CoreTemplates\Tabs\TabContainerItem;
use Newageerp\SfReactTemplates\Event\ListCreatableEvent;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TableService
{
    public const NOWRAP = 0;
    public const WRAPWITHCARD = 10;
    public const WRAPWITHCARDCOMPACT = 11;

    public const WRAPWITHCARDANDTITLE = 20;
    public const WRAPWITHCARDANDTITLECOMPACT = 21;

    protected TableHeaderService $tableHeaderService;

    protected TableRowService $tableRowService;

    protected TabsUtilsV3 $tabsUtilsV3;

    protected EntitiesUtilsV3 $entitiesUtilsV3;

    protected PropertiesUtilsV3 $propertiesUtilsV3;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        TableHeaderService $tableHeaderService,
        TableRowService $tableRowService,
        TabsUtilsV3 $tabsUtilsV3,
        EntitiesUtilsV3 $entitiesUtilsV3,
        PropertiesUtilsV3 $propertiesUtilsV3,
        EventDispatcherInterface $eventDispatcher,
    ) {
        $this->tableHeaderService = $tableHeaderService;
        $this->tableRowService = $tableRowService;
        $this->tabsUtilsV3 = $tabsUtilsV3;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
        $this->propertiesUtilsV3 = $propertiesUtilsV3;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function buildListDataSourceWithToolbar(
        string $schema,
        string $type,
        array $eventData = []
    ) {
        // toolbar
        $tab = $this->getTabsUtilsV3()->getTabBySchemaAndType(
            $schema,
            $type,
        );
        if ($tab) {
            $listDataSource = $this->buildListDataSource(
                $schema,
                $type,
            );

            $listTable = $this->buildTableData(
                $schema,
                $type,
            );

            $totals = $this->tabsUtilsV3->getTabTotals($schema, $type);

            if (isset($tab['summary']) && $tab['summary']) {
                $tabContainer = new TabContainer();

                $tabContainerItem = new TabContainerItem('Data');
                $tabContainer->addItem($tabContainerItem);
                $tabContainerItem->getContent()->addTemplate($listTable);
                if ($totals) {
                    $listTotals = new ListDataTotals($totals);
                    $tabContainerItem->getContent()->addTemplate($listTotals);
                }

                $tabContainerItem = new TabContainerItem('Summary');
                $tabContainer->addItem($tabContainerItem);

                $listDataSummary = new ListDataSummary($schema, $tab['summary']);
                $tabContainerItem->getContent()->addTemplate($listDataSummary);

                $listDataSource->getChildren()->addTemplate($tabContainer);
            } else {
                $listDataSource->getChildren()->addTemplate($listTable);

                if ($totals) {
                    $listTotals = new ListDataTotals($totals);
                    $listDataSource->getChildren()->addTemplate($listTotals);
                }
            }
            // TEMPLATES CALL
            $templateEvent = new LoadTemplateEvent(
                new Placeholder(),
                'TableService.Toolbar',
                [
                    'schema' => $schema,
                    'type' => $type,
                    'listDataSource' => $listDataSource,
                    ...$eventData,
                ]
            );
            $this->eventDispatcher->dispatch($templateEvent, LoadTemplateEvent::NAME);
        }

        return $listDataSource;
    }

    public function buildListDataSourceForRel(
        string $schema,
        string $type,
        string $targetKey,
        int $elementId,
        ?int $wrapWithCard = self::NOWRAP,
    ): Template {
        $listDataSource = $this->buildListDataSourceWithToolbar(
            $schema,
            $type,
            [
                'relElementId' => $elementId,
                'relTargetKey' => $targetKey,
                'addTitle' => ($wrapWithCard >= self::WRAPWITHCARDANDTITLE),
                'isCompact' => true,
            ]
        );
        $listDataSource->setHidePageSelectionSelect(true);
        $filters = $listDataSource->getExtraFilters();
        $filters[] = [
            'and' => [
                ['i.' . $targetKey, '=', $elementId, true]
            ]
        ];
        $listDataSource->setExtraFilters($filters);
        // $listTable = $this->buildTableData(
        //     $schema,
        //     $type,
        // );
        $listDataSource->setSocketData([
            'id' => $targetKey . '.' . $schema . '.' . $type . '.rel',
            'data' => [
                $schema . '.' . $targetKey . '.id' => $elementId,
            ]
        ]);
        // $listDataSource->getChildren()->addTemplate($listTable);

        // $tab = $this->getTabsUtilsV3()->getTabBySchemaAndType(
        //     $schema,
        //     $type,
        // );
        // if ($tab) {
        //     // TABS EXPORT
        //     if (isset($tab['exports']) && $tab['exports']) {
        //         $summary = isset($tab['summary']) ? $tab['summary'] : [];

        //         $listDataSource->getToolbar()->getToolbarRight()->addTemplate(
        //             new ToolbarExport($schema, $tab['exports'], $summary)
        //         );
        //     }

        //     // SORT
        //     $sort = $this->getTabsUtilsV3()->getTabSort(
        //         $schema,
        //         $type,
        //     );
        //     if (count($sort) > 0) {
        //         $listDataSource->getToolbar()->getToolbarRight()->addTemplate(
        //             new ToolbarSort($schema, $sort)
        //         );
        //     }

        //     // DETAILED SEARCH
        //     $listDataSource->getToolbar()->getToolbarRight()->addTemplate(
        //         new ToolbarDetailedSearch($schema)
        //     );
        // }

        // // TOTALS
        // $totals = $this->tabsUtilsV3->getTabTotals($schema, $type);
        // if ($totals) {
        //     $listTotals = new ListDataTotals($totals);
        //     $listDataSource->getChildren()->addTemplate($listTotals);
        // }

        if ($wrapWithCard >= self::WRAPWITHCARD) {
            $whiteCard = new WhiteCard();
            if ($wrapWithCard === self::WRAPWITHCARDCOMPACT) {
                $whiteCard->setIsCompact(true);
            }
            if ($wrapWithCard >= self::WRAPWITHCARDANDTITLE) {
                if ($wrapWithCard === self::WRAPWITHCARDANDTITLECOMPACT) {
                    $whiteCard->setIsCompact(true);
                }
            }
            $whiteCard->getChildren()->addTemplate($listDataSource);
            return $whiteCard;
        }

        return $listDataSource;
    }

    public function buildListDataSource(string $schema, string $type): ListDataSource
    {
        $tab = $this->tabsUtilsV3->getTabBySchemaAndType($schema, $type);
        $tabSort = $this->tabsUtilsV3->getTabSort($schema, $type);
        $totals = $this->tabsUtilsV3->getTabTotals($schema, $type);

        $listDataSource = new ListDataSource($schema, $type);
        $listDataSource->setSort($tabSort);
        $listDataSource->setTotals($totals);
        if (isset($tab['pageSize']) && $tab['pageSize']) {
            $listDataSource->setPageSize($tab['pageSize']);
        }

        $filters = [];
        if ($fs = $this->tabsUtilsV3->getTabFilter($schema, $type)) {
            $filters[] = $fs;
        }
        $listDataSource->setExtraFilters($filters);

        return $listDataSource;
    }

    public function buildTableData(string $schema, string $type, ?bool $addSelectButton = false): ListDataTable
    {

        $thead = $this->tableHeaderService->buildHeaderRow(
            $schema,
            $type,
            $addSelectButton,
        );
        $tbody = $this->tableRowService->buildDataRow(
            $schema,
            $type,
            $addSelectButton,
        );

        $tableData = new ListDataTable();
        $tableData->getHeader()->addTemplate($thead);
        $tableData->getRow()->addTemplate($tbody);

        return $tableData;
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
     * Get the value of propertiesUtilsV3
     *
     * @return PropertiesUtilsV3
     */
    public function getPropertiesUtilsV3(): PropertiesUtilsV3
    {
        return $this->propertiesUtilsV3;
    }

    /**
     * Set the value of propertiesUtilsV3
     *
     * @param PropertiesUtilsV3 $propertiesUtilsV3
     *
     * @return self
     */
    public function setPropertiesUtilsV3(PropertiesUtilsV3 $propertiesUtilsV3): self
    {
        $this->propertiesUtilsV3 = $propertiesUtilsV3;

        return $this;
    }
}
