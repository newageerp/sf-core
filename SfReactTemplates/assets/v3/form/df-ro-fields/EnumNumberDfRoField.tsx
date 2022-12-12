import React from 'react'
import { useDfValue } from '../../hooks/useDfValue';
import { Int } from '@newageerp/data.table.base';
import { getPropertyForPath } from '../../utils';


interface Props {
  fieldKey: string;
  id: number;
  options?: any[];
}

export default function EnumNumberDfRoField(props: Props) {
  const value = useDfValue({ id: props.id, path: props.fieldKey });
  const prop = getPropertyForPath(props.fieldKey);
  const options = props.options ? props.options : prop?.enum;

  let displayValue = 0;
  if (options) {
    options.forEach(o => {
      if (o.value === value) {
        displayValue = o.label;
      }
    })
  }
  return (
    <Int value={displayValue} />
  )
}
