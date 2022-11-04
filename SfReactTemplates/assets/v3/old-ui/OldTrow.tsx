import React from 'react'
import OldTd, { TableTdProps } from './OldTd'

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
  const className = []
  if (props.className) {
    className.push(props.className)
  }
  return (
    <tr className={className.join(' ')}>
      {props.extraContentStart}
      {props.columns.map((col, index: number) => {
        return (
          <OldTd key={'col-' + index} {...col.props}>
            {col.content}
          </OldTd>
        )
      })}
      {props.extraContentEnd}
    </tr>
  )
}
