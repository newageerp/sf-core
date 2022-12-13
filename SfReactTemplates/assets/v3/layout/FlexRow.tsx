import React from 'react'
import classNames from 'classnames'
import { Template, TemplatesParser } from '@newageerp/v3.templates.templates-core'

interface Props {
  children: Template[],
  className: string,
}

export default function FlexRow(props: Props) {
  return (
    <div className={classNames('tw3-flex', props.className)}>
      <TemplatesParser templates={props.children} />
    </div>
  )
}
