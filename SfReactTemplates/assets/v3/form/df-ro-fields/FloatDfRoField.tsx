import React from 'react'
import { useDfValue } from '../../hooks/useDfValue';
import { Float } from '@newageerp/data.table.float'

interface Props {
  fieldKey: string;
  id: number;
  stringInline?: boolean;
  accuracy?: number;
}

export default function FloatDfRoField(props: Props) {
  const value = useDfValue({ id: props.id, path: props.fieldKey });

  return (
    <Float value={value} inline={props.stringInline} accuracy={props.accuracy} />
  )
}
