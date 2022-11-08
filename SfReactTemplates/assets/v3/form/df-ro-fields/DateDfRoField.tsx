import React from 'react'
import { useDfValue } from '../../hooks/useDfValue';
import { Date } from '@newageerp/data.table.date'

interface Props {
  fieldKey: string;
  id: number;
}

export default function DateDfRoField(props: Props) {
  const value = useDfValue({ id: props.id, path: props.fieldKey });

  return (
    <Date value={value} format={process.env.REACT_APP_DATE_FORMAT} />
  )
}
