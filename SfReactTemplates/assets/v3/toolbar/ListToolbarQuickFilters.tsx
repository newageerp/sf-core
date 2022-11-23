import React, { Fragment, useState } from 'react'
import { useTemplateLoader } from '../templates/TemplateLoader';
import { useTranslation } from 'react-i18next';
import { FilterListData } from "@newageerp/sfs.list-toolbar.filter.filter-list-data"
import { CompactRow } from "@newageerp/ui.form.base.form-pack";
import { FieldLabel, FieldDateRangeFilter } from '@newageerp/v3.bundles.form-bundle'
import { ToolbarButtonWithMenu } from '@newageerp/v3.bundles.buttons-bundle';


interface Props {
  filters: any[],
  showLabels?: boolean;
}

export default function ListToolbarQuickFilters(props: Props) {
  const { t } = useTranslation();
  const [showFilters, setShowFilters] = useState(props.filters.length > 2);
  const showButton = props.filters.length > 2;

  const { data: tData } = useTemplateLoader();
  const { onAddExtraFilter } = tData;

  if (showButton) {
    return (
      <ToolbarButtonWithMenu
        button={{ iconName: 'filter-list' }}
        menu={{
          children: <ListToolbarQuickFiltersInner {...props} />
        }} />
    )
  }

  return (
    <ToolbarButtonWithMenu
      button={{ iconName: 'filter-list' }}
      menu={{
        children: <ListToolbarQuickFiltersInner {...props} />
      }} />
  )

}

const ListToolbarQuickFiltersInner = (props: Props) => {
  const { data: tData } = useTemplateLoader();
  const { onAddExtraFilter } = tData;

  return (
    <Fragment>

      {props.filters.map((filter: any, fIndex) => {
        const value = <Fragment>
          {filter.type === 'date' && <FieldDateRangeFilter path={filter.path} onAddExtraFilter={onAddExtraFilter} />}
          {filter.type === 'datetime' && <FieldDateRangeFilter path={filter.path} onAddExtraFilter={onAddExtraFilter} />}
          {filter.type === 'object' && <FilterListData path={filter.path} onAddExtraFilter={onAddExtraFilter} schema={filter.property.typeFormat} field={"_viewTitle"} iconName={filter.iconName} sort={filter.sort} />}
        </Fragment>

        if (props.showLabels) {
          return <CompactRow key={`f-${fIndex}`} control={value} label={<FieldLabel>{filter.property?.title}</FieldLabel>} />
        }

        return (
          <Fragment key={`f-${fIndex}`}>
            {value}
            {/* {filter.type === 'status' && <FilterListOptions options={{{ schemaUC}}StatusesList['{{ qfilter.property.key }}'].map(s => ({value: s.status, label: s.text }))} path={"{{ qfilter.path }}"} onAddExtraFilter={onAddExtraFilter} iconName={"{% if qfilter.iconName %}{{ qfilter.iconName }}{% else %}diagram-project{% endif %}"} /> } */}
          </Fragment>
        )
      })}
    </Fragment>
  )

}
