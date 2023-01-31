import React from 'react'
import { TableTdProps } from './OldTd'
import { Td } from '@newageerp/v3.bundles.layout-bundle'

export interface TrowCol {
  props: TableTdProps
  content: any
}

export interface TrowProps {
  columns: TrowCol[]
  className?: string,
  extraContentEnd?: any,
  extraContentStart?: any,
}

export const defaultStrippedRowClassName = (index: number) => index % 2 === 0 ? "bg-gray-100" : "";

export default function OldTrow(props: TrowProps) {
  const className : string[] = []
  if (props.className) {
    className.push(props.className)
  }
  return (
    <tr className={className.join(' ')}>
      {props.extraContentStart}
      {props.columns.map((col, index: number) => {
        return (
          <Td key={'col-' + index}>
            {col.content}
          </Td>
        )
      })}
      {props.extraContentEnd}
    </tr>
  )
}
