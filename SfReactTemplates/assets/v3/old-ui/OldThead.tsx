import React, { Fragment } from 'react'
import { TheadCol } from './OldTable'
import Th from './OldTh'

interface TheadProps {
  columns: (TheadCol | undefined)[],
  extraContentEnd?: any,
  extraContentStart?: any,
  className?: string
}

export default function OldThead(props: TheadProps) {
  return (
    <thead className={props.className ? props.className : ''}>
      <tr>
        {props.extraContentStart}
        {props.columns.filter(c => c !== undefined).map((col: (TheadCol | undefined), index: number) => {
          if (!col) {
            return <Fragment  key={'col-' + index}/>
          }
          return (
            <Th key={'col-' + index} {...col.props}>
              {col.content}
            </Th>
          )
        })}
        {props.extraContentEnd}
      </tr>
    </thead>
  )
}
