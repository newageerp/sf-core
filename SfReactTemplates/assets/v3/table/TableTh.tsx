import classNames from 'classnames'
import React from 'react'
import { Template, TemplatesParser, useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { Th } from '@newageerp/ui.ui-bundle'
import { ServerFilterItem } from '@newageerp/ui.ui-bundle'
import { Icon, IconType } from '@newageerp/ui.ui-bundle';

interface Props {
  contents: Template[],
  className?: string,
  textAlignment?: string,
  filter?: ServerFilterItem,
}

export default function TableTh(props: Props) {
  const { data: tData } = useTemplatesLoader();

  const hasFilter = !!props.filter;

  return (
    <Th className={classNames(props.className, {'tw3-whitespace-nowrap': hasFilter})} textAlignment={props.textAlignment ? props.textAlignment : undefined}>
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
