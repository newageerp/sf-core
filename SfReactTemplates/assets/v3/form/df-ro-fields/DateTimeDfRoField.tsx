import React from 'react'
import { useDfValue } from '../../hooks/useDfValue';
import {Datetime} from '@newageerp/data.table.datetime'

interface Props {
  fieldKey: string;
  id: number;
}

export default function DateTimeDfRoField(props: Props) {
  const value = useDfValue({ id: props.id, path: props.fieldKey });

  return (
    <Datetime value={value}/>
  )
}
