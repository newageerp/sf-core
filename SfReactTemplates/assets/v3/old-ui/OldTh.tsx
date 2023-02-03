import { Th } from '@newageerp/v3.bundles.layout-bundle'
import React from 'react'

import { TableBorderColor, TableSize } from './OldTable'

export interface TableThProps
  extends React.DetailedHTMLProps<
    React.ThHTMLAttributes<HTMLTableHeaderCellElement>,
    HTMLTableHeaderCellElement
  > {
  size?: TableSize | string
}

export default function OldTh(props: TableThProps) {
  const className = [
    
  ]
  const size = props.size ? props.size : TableSize.base

  // if (size === TableSize.lg) {
  //   className.push('text-sm')
  //   className.push('px-4 py-4')
  // } else if (size === TableSize.sm) {
  //   className.push('text-xxs')
  //   className.push('px-1 py-1')
  // } else {
  //   className.push('text-xs')
  //   className.push('px-2 py-2')
  // }
  if (props.className) {
    className.push(props.className)
  }

  const tdProps = { ...props };
  delete tdProps.size;

  return (
    <Th className={className.join(' ')}>
      {props.children}
    </Th>
  )
}
