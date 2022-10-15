import classNames from 'classnames'
import React from 'react'
import { Template, TemplatesParser, useTemplateLoader } from '../templates/TemplateLoader';
import { Th } from '@newageerp/ui.table.base.table'
import { ServerFilterItem } from '@newageerp/ui.components.list.filter-container'
import { Icon, IconType } from '@newageerp/ui.icons.base.icon';

interface Props {
  contents: Template[],
  className?: string,
  textAlignment?: string,
  filter?: ServerFilterItem,
}

export default function TableTh(props: Props) {
  const { data: tData } = useTemplateLoader();

  const hasFilter = !!props.filter;

  return (
    <Th className={classNames(props.className, {'whitespace-nowrap': hasFilter})} textAlignment={props.textAlignment ? props.textAlignment : undefined}>
      <TemplatesParser templates={props.contents} />
      {hasFilter && <button onClick={() => tData.addNewBlockFilter(props.filter)} className={"tw3-ml-2"}>
        <Icon
          icon='filter'
          type={IconType.Light}
        />
      </button>}
    </Th>
  )
}
