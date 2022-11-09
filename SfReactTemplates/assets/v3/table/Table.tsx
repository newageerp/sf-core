import classNames from 'classnames'
import React from 'react'
import { Template, TemplatesParser } from '../templates/TemplateLoader'
import { Table } from '@newageerp/ui.table.base.table'

interface Props {
    head: Template[],
    body: Template[],
    className?: string
}

export default function Table(props: Props) {
  return (
    <Table 
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
