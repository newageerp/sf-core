import classNames from 'classnames'
import React from 'react'
import { Template, TemplatesParser } from '@newageerp/v3.templates.templates-core'
import { Table as TableTpl } from '@newageerp/ui.ui-bundle'

interface Props {
    head: Template[],
    body: Template[],
    className?: string
}

export default function Table(props: Props) {
  return (
    <TableTpl 
      className={classNames(props.className)}
      thead={
        <thead>
          <TemplatesParser templates={props.head} />
        </thead>
      }
      tbody={
        <thead>
          <TemplatesParser templates={props.body} />
        </thead>
      }
    />
  )
}
