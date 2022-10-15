<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfAuth\Service\AuthService;
use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfControlpanel\Console\TabsUtilsV3;
use Newageerp\SfReactTemplates\CoreTemplates\Cards\WhiteCard;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarDetailedSearch;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarExport;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarNewButton;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarQs;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarQuickFilters;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarSort;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarTabSwitch;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

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

    public function __construct(
        TableHeaderService $tableHeaderService,
        TableRowService $tableRowService,
        TabsUtilsV3 $tabsUtilsV3,
        EntitiesUtilsV3 $entitiesUtilsV3,
        PropertiesUtilsV3 $propertiesUtilsV3,
    ) {
        $this->tableHeaderService = $tableHeaderService;
        $this->tableRowService = $tableRowService;
        $this->tabsUtilsV3 = $tabsUtilsV3;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
        $this->propertiesUtilsV3 = $propertiesUtilsV3;
    }

    public function buildListDataSourceWithToolbar(
        string $schema,
        string $type,
    ) {
        $listDataSource = $this->buildListDataSource(
            $schema,
            $type,
        );
        $listTable = $this->buildTableData(
            $schema,
            $type,
        );
        $listDataSource->getChildren()->addTemplate($listTable);

        // toolbar
        $tab = $this->getTabsUtilsV3()->getTabBySchemaAndType(
            $schema,
            $type,
        );
        if ($tab) {
            // CREATE BUTTON
            $disableCreate = isset($tab['disableCreate']) && $tab['disableCreate'];
            if (
                !$disableCreate &&
                $this->getEntitiesUtilsV3()->checkIsCreatable(
                    $schema,
                    AuthService::getInstance()->getUser()->getPermissionGroup(),
                )
            ) {
                $listDataSource->getToolbar()->getToolbarLeft()->addTemplate(
                    new ToolbarNewButton($schema)
                );
            }

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

            $quickFilters = $this->getTabsUtilsV3()->getTabQuickFilters(
                $schema,
                $type,
            );
            if (count($quickFilters) > 0) {
                $quickFilters = array_map(
                    function ($item) {
                        $item['property'] = $this->getPropertiesUtilsV3()->getPropertyForPath($item['path']);
                        $item['type'] = $this->getPropertiesUtilsV3()->getPropertyNaeType($item['property'], []);
        
                        $pathA = explode(".", $item['path']);
                        $pathA[0] = 'i';
                        $item['path'] = implode(".", $pathA);
        
                        if ($item['type'] === 'object') {
                            $item['sort'] = $this->getEntitiesUtilsV3()->getDefaultSortForSchema($item['property']['typeFormat']);
                        }
        
                        return $item;
                    },
                    $quickFilters
                );

                $listDataSource->getToolbar()->getToolbarLeft()->addTemplate(
                    new ToolbarQuickFilters($quickFilters)
                );
            }

            // TABS SWITCH
            $tabsSwitch = $this->getTabsUtilsV3()->getTabsSwitchOptions(
                $schema,
                $type,
            );
            if (count($tabsSwitch) > 0) {
                $listDataSource->getToolbar()->getToolbarLeft()->addTemplate(
                    new ToolbarTabSwitch(
                        $schema,
                        $type,
                        $tabsSwitch
                    )
                );
            }

            // TABS EXPORT
            if (isset($tab['exports']) && $tab['exports']) {
                $listDataSource->getToolbar()->getToolbarRight()->addTemplate(
                    new ToolbarExport($schema, $tab['exports'])
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

        return $listDataSource;
    }

    public function buildListDataSourceForRel(
        string $schema,
        string $type,
        string $targetKey,
        int $elementId,
        ?int $wrapWithCard = self::NOWRAP,
    ): Template {
        $listDataSource = $this->buildListDataSource(
            $schema,
            $type,
        );
        $filters = $listDataSource->getExtraFilters();
        $filters[] = [
            'and' => [
                ['i.' . $targetKey, '=', $elementId, true]
            ]
        ];
        $listDataSource->setExtraFilters($filters);
        $listTable = $this->buildTableData(
            $schema,
            $type,
        );
        $listDataSource->setSocketData([
            'id' => $targetKey . '.' . $schema . '.' . $type . '.rel',
            'data' => [
                $schema . '.' . $targetKey . '.id' => $elementId,
            ]
        ]);
        $listDataSource->getChildren()->addTemplate($listTable);

        if ($wrapWithCard >= self::WRAPWITHCARD) {
            $whiteCard = new WhiteCard();
            if ($wrapWithCard === self::WRAPWITHCARDCOMPACT) {
                $whiteCard->setIsCompact(true);
            }
            if ($wrapWithCard >= self::WRAPWITHCARDANDTITLE) {
                $whiteCard->setTitle($this->getEntitiesUtilsV3()->getTitlePluralBySlug($schema));
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

        $listDataSource = new ListDataSource($schema, $type);
        $listDataSource->setSort($tabSort);
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
