import classNames from 'classnames'
import React from 'react'
import { Template, TemplatesParser, useTemplateLoader } from '../templates/TemplateLoader';
import { Td } from '@newageerp/ui.table.base.table'

interface Props {
  contents: Template[],
  className?: string,
  textAlignment?: string,
}

export default function TableTd(props: Props) {
  const { data: tData } = useTemplateLoader();

  return (
    <Td
      className={classNames(props.className)}
      textAlignment={props.textAlignment ? props.textAlignment : undefined}
    >
      <TemplatesParser templates={props.contents} />
    </Td>
  )
}
