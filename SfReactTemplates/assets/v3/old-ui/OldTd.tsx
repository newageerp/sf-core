import React from 'react'
import { TableBorderColor, TableSize } from './OldTable'

export interface TableTdProps
  extends React.DetailedHTMLProps<
  React.TdHTMLAttributes<HTMLTableDataCellElement>,
  HTMLTableDataCellElement
  > {
  size?: TableSize | string
}

export default function OldTd(props: TableTdProps) {
  const className = ['border-t border-' + TableBorderColor]
  const size = props.size ? props.size : TableSize.base

  if (size === TableSize.lg) {
    className.push('text-base')
    className.push('px-4 py-4')
  } else if (size === TableSize.sm) {
    className.push('text-xs')
    className.push('px-1 py-1')
  } else {
    className.push('text-sm')
    className.push('px-2 py-2')
  }
  if (props.className) {
    className.push(props.className)
  }

  const tdProps = { ...props };
  delete tdProps.size;

  return (
    <td {...tdProps} className={className.join(' ')}>
      {props.children}
    </td>
  )
}
