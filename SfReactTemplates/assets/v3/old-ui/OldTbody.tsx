import React, { Fragment } from 'react'
import { groupMap } from '../../../core/components/utils/mapUtils';
import Td from './OldTd';
import Trow, { TrowProps } from './OldTrow'

export interface TbodyProps<Type> {
  data: Type[]
  callback: (item: Type, index: number) => TrowProps,
  group?: (item: Type) => string,
}

export default function OldTbody<Type extends object>(props: TbodyProps<Type>) {
  const { data, callback } = props

  if (props.group) {
    const groupedData = groupMap(data, props.group);

    return (
      <tbody>
        {Object.keys(groupedData).map((groupTitle: string, groupIndex: number) => {
          const _data = groupedData[groupTitle];
          return (
            <Fragment key={`group-${groupTitle}-${groupIndex}`}>
              <tr>
                <Td colSpan={99} className={"bg-blue-50 font-medium"}>
                  {groupTitle}
                </Td>
              </tr>
              {_data.map((item: Type, index: number) => {
                const rowProps = callback(item, index)

                let tRowKey = 'row-' + index;
                if (typeof item === 'object' && 'id' in item) {
                  // @ts-ignore
                  tRowKey = 'row-' + item.id;
                }

                return <Trow key={tRowKey} {...rowProps} />
              })}
            </Fragment>
          )
        })}
      </tbody>
    )
  }


  return (
    <tbody>
      {data.map((item: Type, index: number) => {
        const rowProps = callback(item, index)

        let tRowKey = 'row-' + index;
        if (typeof item === 'object' && 'id' in item) {
          // @ts-ignore
          tRowKey = 'row-' + item.id;
        }

        return <Trow key={tRowKey} {...rowProps} />
      })}
    </tbody>
  )
}
