import classNames from 'classnames'
import React from 'react'
import { Template, TemplatesParser } from '@newageerp/v3.templates.templates-core'

interface Props {
    contents: Template[],
    className?: string
}

export default function TableTr(props: Props) {
  return (
    <tr className={classNames(props.className)}>
        <TemplatesParser templates={props.contents} />
    </tr>
  )
}
