import classNames from 'classnames'
import React from 'react'
import { Template, TemplatesParser, useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { Td } from '@newageerp/v3.bundles.layout-bundle'

interface Props {
  contents: Template[],
  className?: string,
  textAlignment?: string,
}

export default function TableTd(props: Props) {
  const { data: tData } = useTemplatesLoader();

  return (
    <Td
      className={classNames(props.className)}
      textAlignment={props.textAlignment ? props.textAlignment : undefined}
    >
      <TemplatesParser templates={props.contents} />
    </Td>
  )
}
