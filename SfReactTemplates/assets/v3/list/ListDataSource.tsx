import React, { useEffect, useState, useRef, Fragment } from "react";
import { OpenApi } from "@newageerp/nae-react-auth-wrapper";
import { functions, UI } from "@newageerp/nae-react-ui";
import {
  PageContainer,
} from "@newageerp/ui.paging.base.page-container";
import { SortingItem } from "@newageerp/ui.components.list.sort-controller";
import { TypeItemFilters } from "@newageerp/ui.components.list.filter-container";
import { useLocationState } from "use-location-state";
import { ServerFilterItem } from "@newageerp/ui.components.list.filter-container";
import TemplateLoader, { Template } from "../templates/TemplateLoader";
import { FilterContainer } from '@newageerp/ui.components.list.filter-container';
import { useTemplateLoader } from '../templates/TemplateLoader';
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
}

export default function ListDataSource(props: Props) {
  const { data: tData } = useTemplateLoader();

  const [extendedSearchOptions, setExtendedSearchOptions] = useState<any[]>([]);

  const [itemsFilter, setItemsFilter] = useState<TypeItemFilters>([]);
  const [showExtendedSearch, setShowExtendedSearch] = useState(false);
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

  const tab = functions.tabs.getTabFromSchemaAndType(props.schema, props.type);
  const fieldsToReturn = functions.tabs.getTabFieldsToReturn(tab);

  const [getData, dataResult] = OpenApi.useUList(props.schema, fieldsToReturn);

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
      UI.Socket.Service.subscribeToList(
        {
          key: props.socketData.id,
          data: props.socketData.data,
        },
        loadData
      );
    }

    return () => {
      if (props.socketData) {
        UI.Socket.Service.unSubscribeFromList({ key: props.socketData.id });
      }
    };
  }, [props.socketData]);

  useEffect(scrollToHeader, [dataResult.data.data]);

  const records = dataResult.data.records;
  const pages = Math.ceil(records / props.pageSize);

  const dataToRender = dataResult.data.data;

  return (
    <Fragment>
      <TemplateLoader
        templates={props.toolbar}
        templateData={
          {
            defaults: {
              quickSearch: dataState?.extraFilter?.__qs?._,
            },
            onAddExtraFilter,
            sort: {
              value: dataState?.sort,
              onChange: setSort,
            },
            filter: {
              prepareFilter,
            },
            extendedSearch: {
              value: showExtendedSearch,
              onChange: setShowExtendedSearch,
              properties: {
                value: extendedSearchOptions,
                onChange: setExtendedSearchOptions,
              }

            },

          }
        }
      />

      {showExtendedSearch && extendedSearchOptions.length > 0 && (
        <FilterContainer
          onCloseFilter={() => setShowExtendedSearch(false)}
          modalProperties={extendedSearchOptions}
          onFilter={setDetailedSearch}
          itemsFilter={itemsFilter}
          setItemsFilter={setItemsFilter}
        />
      )}

      <div>
        <div style={{ margin: "0 -0.5rem" }}>
          <TemplateLoader
            templates={props.children}
            templateData={{
              addNewBlockFilter: addNewBlockFilter,
              dataToRender: dataToRender,
              onAddSelectButton: tData.onAddSelectButton,
            }}
          />
        </div>
      </div>

      {!props.hidePaging && pages > 1 && (
        <PageContainer
          pages={pages}
          activePage={dataState.page}
          setActivePage={setActivePage}
        // marginTop={PageContainerMarginTop[]}
        />
      )}
    </Fragment>
  );
}
