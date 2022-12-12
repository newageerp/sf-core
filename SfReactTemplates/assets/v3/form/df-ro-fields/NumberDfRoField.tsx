import React from 'react'
import {Int} from '@newageerp/data.table.base'
import { useDfValue } from '../../hooks/useDfValue';

interface Props {
  fieldKey: string;
  id: number;
}

export default function NumberDfRoField(props: Props) {
  const value = useDfValue({ id: props.id, path: props.fieldKey });

  return (
    <Int value={value}/>
  )
}
