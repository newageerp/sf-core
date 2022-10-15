import React, { Fragment, useState } from 'react'
import { useTemplateLoader } from '../templates/TemplateLoader';
import { useTranslation } from 'react-i18next';
import { FilterListData } from "@newageerp/sfs.list-toolbar.filter.filter-list-data"
import { FilterListOptions } from "@newageerp/sfs.list-toolbar.filter.filter-list-options"
import { FilterDate } from "@newageerp/sfs.list-toolbar.filter.filter-date"

interface Props {
  filters: any[],
}

export default function ListToolbarQuickFilters(props: Props) {
  const { t } = useTranslation();

  const { data: tData } = useTemplateLoader();
  const { onAddExtraFilter } = tData;

  return (
    <Fragment>
      {props.filters.map((filter: any, fIndex) => {
        return (
          <Fragment key={`f-${fIndex}`}>
            {filter.type === 'date' && <FilterDate path={filter.path} onAddExtraFilter={onAddExtraFilter} />}
            {filter.type === 'object' && <FilterListData path={filter.path} onAddExtraFilter={onAddExtraFilter} schema={filter.property.typeFormat} field={"_viewTitle"} iconName={filter.iconName} sort={filter.sort} /> }
            {/* {filter.type === 'status' && <FilterListOptions options={{{ schemaUC}}StatusesList['{{ qfilter.property.key }}'].map(s => ({value: s.status, label: s.text }))} path={"{{ qfilter.path }}"} onAddExtraFilter={onAddExtraFilter} iconName={"{% if qfilter.iconName %}{{ qfilter.iconName }}{% else %}diagram-project{% endif %}"} /> } */}
          </Fragment>
        )
      })}
    </Fragment>
  )

}
