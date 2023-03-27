import React, { useEffect, useState, useRef, Fragment, ReactNode } from "react";
import { SortingItem } from "@newageerp/ui.ui-bundle";
import { useLocationState } from "use-location-state";

import { FilterContainer, ServerFilterItem, TypeItemFilters } from '@newageerp/v3.bundles.form-bundle';
import { useTemplatesLoader, TemplatesLoader, Template } from '@newageerp/v3.templates.templates-core';
import { getTabFieldsToReturn } from "../utils";
import { SFSSocketService } from "../navigation/NavigationComponent";
import { ListDataSourceProviderContext } from "@newageerp/v3.app.list.list-data-source";
import { useUList } from "@newageerp/v3.bundles.hooks-bundle";
import { useUIBuilder } from "@newageerp/v3.app.mvc.ui-builder";
import { Pagination } from "@newageerp/v3.bundles.layout-bundle";

interface Props {
  children: Template[];
  hidePaging?: boolean;
  sort: SortingItem[];
  totals: any;
  schema: string;
  type: string;
  extraFilters?: any;

  scrollToHeaderOnLoad?: boolean;
  disableVerticalMargin?: boolean;

  socketData?: {
    data: any;
    id: string;
  };

  pageSize: number,

  toolbar: Template[],
  toolbarLine2: Template[],

  hidePageSelectionSelect?: boolean,

  compact: boolean,
}

export default function ListDataSource(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const [reloadKey, setReloadKey] = useState<any>();

  const defaultState: any = {
    page: 1,
    sort: props.sort,
    quickSearch: "",
    detailedSearch: [],
    extraFilter: {},
  };
  const [dataState, setDateState] = useLocationState(
    `${props.schema}${props.type}TableDataSource`,
    defaultState
  );

  // LIST DATA SOURCE CONTENT START
  const [extraTbody, setExtraTbody] = useState([]);
  const [extraThead, setExtraThead] = useState([]);

  const addExtraTbody = (val: ReactNode) => {
    const _extraTbody = JSON.parse(JSON.stringify(extraTbody));
    _extraTbody.push(val);
    setExtraTbody(_extraTbody);
  }
  const addExtraThead = (val: ReactNode) => {
    const _extraThead = JSON.parse(JSON.stringify(extraThead));
    _extraThead.push(val);
    setExtraThead(_extraThead);
  }
  useEffect(() => {
    if (tData.extraListDataTableHead) {
      addExtraThead(tData.extraListDataTableHead);
    }
    if (tData.extraListDataTableBody) {
      addExtraTbody(tData.extraListDataTableBody);
    }
  }, []);

  // LIST DATA SOURCE CONTENT FINISH

  // SELECTED ITEMS START
  const [selectedItems, setSelectedItems] = useState<number[]>(tData?.data?.selection?.items ? tData?.data?.selection?.items : []);
  const addSelectedItem = (element: any) => {
    const _selectedItems: number[] = JSON.parse(JSON.stringify(selectedItems));
    setSelectedItems([..._selectedItems, element.id]);
    tData.onAddSelectButton(element);
  }

  // SELECTED ITEMS FINISH

  const { getTabFromSchemaAndType } = useUIBuilder();

  const [extendedSearchOptions, setExtendedSearchOptions] = useState<any[]>([]);

  const [itemsFilter, setItemsFilter] = useState<TypeItemFilters>(dataState.detailedSearch ? dataState.detailedSearch : []);
  const [showExtendedSearch, setShowExtendedSearch] = useState(!!dataState.detailedSearch && dataState.detailedSearch.length > 0 ? true : false);
  const addNewBlockFilter = (filter: ServerFilterItem) => {
    setShowExtendedSearch(true);

    const _itemsFilter: TypeItemFilters = JSON.parse(
      JSON.stringify(itemsFilter)
    );

    _itemsFilter.push({
      id: Date.now(),
      filter: [
        {
          filterId: Date.now(),
          items: [
            {
              itemId: Date.now(),
              filterValue: "",
              selectedFilter: filter,
            },
          ],
        },
      ],
    });

    setItemsFilter(_itemsFilter);
  };

  const onAddExtraFilter = (key: string, data: any) => {
    const _extraFilter = JSON.parse(JSON.stringify(dataState.extraFilter));
    _extraFilter[key] = data;

    if (!data && !(key in _extraFilter)) {
      return;
    }

    if (JSON.stringify(_extraFilter) !== JSON.stringify(dataState.extraFilter)) {
      const _state = JSON.parse(JSON.stringify(dataState));
      _state.page = 1;
      _state.extraFilter = _extraFilter;
      setDateState(_state);
    }
  }

  // useEffect(() => {
  //   if (itemsFilter.length > 0 && !showExtendedSearch) {
  //     setShowExtendedSearch(true);
  //   }
  // }, [itemsFilter, showExtendedSearch]);


  const headerRef = useRef(null);
  const scrollToHeader = () => {
    if (headerRef && headerRef.current && props.scrollToHeaderOnLoad) {
      // @ts-ignore
      headerRef.current.scrollIntoView({ behavior: "smooth", block: "start" });
    }
  };


  const setActivePage = (page: number) => {
    if (page !== dataState.page) {
      const _state = JSON.parse(JSON.stringify(dataState));
      _state.page = page;
      setDateState(_state);
    }
  };
  const setSort = (sort: SortingItem[]) => {
    if (JSON.stringify(sort) !== JSON.stringify(dataState.sort)) {
      const _state = JSON.parse(JSON.stringify(dataState));
      _state.page = 1;
      _state.sort = sort;
      setDateState(_state);
    }
  }
  const setDetailedSearch = (detailedSearch: TypeItemFilters) => {
    if (JSON.stringify(detailedSearch) !== JSON.stringify(dataState.detailedSearch)) {
      const _state = JSON.parse(JSON.stringify(dataState));
      _state.page = 1;
      _state.detailedSearch = detailedSearch;
      setDateState(_state);
    }
  }

  const tab = getTabFromSchemaAndType(props.schema, props.type);
  const fieldsToReturn = getTabFieldsToReturn(tab);

  const [getData, dataResult] = useUList(props.schema, fieldsToReturn);

  const prepareFilter = () => {
    let _filter: any = [{ and: [] }];

    let filter = _filter[0]["and"].length > 0 ? _filter : [];

    if (!!dataState.detailedSearch && dataState.detailedSearch.length > 0) {
      dataState.detailedSearch.forEach((_f: any) => {
        const orFilter: any = { or: [] };
        _f.filter.forEach((_subF: any) => {
          const andFilter: any = { and: [] };
          _subF.items.forEach((itemFilter: any) => {
            andFilter.and.push([
              itemFilter.selectedFilter.id,
              itemFilter.selectedComparison.value,
              itemFilter.filterValue,
            ]);
          });
          orFilter.or.push(andFilter);
        });
        filter.push(orFilter);
      });
    }

    if (!!dataState.extraFilter) {
      const keys = Object.keys(dataState.extraFilter);
      keys.forEach((k) => {
        const fV = dataState.extraFilter[k];
        if (!!fV) {
          filter.push(fV);
        }
      });
    }

    if (props.extraFilters) {
      props.extraFilters.forEach((f: any) => {
        filter.push(f);
      });
    }
    if (tData.listExtraFilters) {
      tData.listExtraFilters.forEach((f: any) => {
        filter.push(f);
      });
    }

    return filter;
  };

  const loadData = () => {
    const filter = prepareFilter();

    getData(
      filter,
      dataState.page,
      props.pageSize,
      dataState.sort.filter((s: SortingItem) => s.key !== ""),
      undefined,
      undefined,
      props.totals
    );
  };
  useEffect(loadData, [dataState, props.extraFilters, props.totals]);

  useEffect(() => {
    if (props.socketData) {
      SFSSocketService.subscribeToList(
        {
          key: props.socketData.id,
          data: props.socketData.data,
        },
        loadData
      );
    }

    return () => {
      if (props.socketData) {
        SFSSocketService.unSubscribeFromList({ key: props.socketData.id });
      }
    };
  }, [props.socketData]);

  useEffect(scrollToHeader, [dataResult.data.data]);

  const records = dataResult.data.records;
  const pages = Math.ceil(records / props.pageSize);
  const dataTotals = dataResult.data.totals;

  const dataToRender = dataResult.data.data;

  return (
    <ListDataSourceProviderContext.Provider
      value={{
        data: {
          selection: {
            items: selectedItems,
            addElement: addSelectedItem
          },
          reload: {
            do: () => {
              setReloadKey(new Date().getTime());
              loadData();
            },
            reloading: dataResult.loading,
            key: reloadKey,
          },
          cache: {
            token: dataResult.data.cacheRequest,
          },
          values: dataToRender,
          totals: dataTotals,
        },
        filter: {
          addBlock: addNewBlockFilter,
          addExtra: onAddExtraFilter,
          extraFilter: dataState.extraFilter,
          form: {
            value: showExtendedSearch,
            onChange: setShowExtendedSearch,
            properties: {
              value: extendedSearchOptions,
              onChange: setExtendedSearchOptions,
            }
          },
          prepare: prepareFilter
        },
        content: {
          addExtraTbody,
          addExtraThead,
          extraTbody,
          extraThead,
        },
        ui: {
          compact: props.compact
        }
      }}>
      {((!!props.toolbar && props.toolbar.length > 0) || (!!props.toolbarLine2 && props.toolbarLine2.length > 0)) &&
        <div className="tw3-space-y-2 tw3-py-4">
          <TemplatesLoader
            templates={props.toolbar}
            templateData={
              {
                defaults: {
                  quickSearch: dataState?.extraFilter?.__qs?._,
                },
                sort: {
                  value: dataState?.sort,
                  onChange: setSort,
                },
                filter: {
                  extraFilter: dataState?.extraFilter,
                },
              }
            }
          />

          {!!props.toolbarLine2 &&

            <TemplatesLoader
              templates={props.toolbarLine2}
              templateData={
                {
                  defaults: {
                    quickSearch: dataState?.extraFilter?.__qs?._,
                  },
                  sort: {
                    value: dataState?.sort,
                    onChange: setSort,
                  },
                  filter: {
                    extraFilter: dataState?.extraFilter,
                  },
                }
              }
            />
          }
        </div>
      }

      {showExtendedSearch && extendedSearchOptions.length > 0 && (
        <FilterContainer
          onCloseFilter={() => setShowExtendedSearch(false)}
          modalProperties={extendedSearchOptions}
          onFilter={setDetailedSearch}
          itemsFilter={itemsFilter}
          setItemsFilter={setItemsFilter}
        />
      )}

      <div className="md:tw3-overflow-visible tw3-overflow-x-auto">
        <div style={{ margin: "0 -0.5rem" }}>
          <TemplatesLoader
            templates={props.children}
            templateData={{
              filter: {
                extraFilter: dataState?.extraFilter,
              },
              sort: dataState?.sort,
              pagingContainer: <Fragment>
                {!props.hidePaging && pages > 1 && (
                  <Pagination
                    pages={pages}
                    activePage={dataState.page}
                    setActivePage={setActivePage}
                    hidePageSelectionSelect={props.hidePageSelectionSelect}
                  // marginTop={PageContainerMarginTop[]}
                  />
                )}
              </Fragment>,
              reloadData: loadData
            }}
          />

        </div>
      </div>


    </ListDataSourceProviderContext.Provider>
  );
}
