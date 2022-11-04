import { String } from '@newageerp/data.table.string';
import React from 'react'
import { useDfValue } from '../../hooks/useDfValue';
import { getPropertyForPath } from '../../utils';

interface Props {
  fieldKey: string;
  id: number;
  options?: any[];
}

export default function EnumTextDfRoField(props: Props) {
  const value = useDfValue({ id: props.id, path: props.fieldKey });
  const prop = getPropertyForPath(props.fieldKey);
  const options = props.options ? props.options : prop?.enum;

  let displayValue = '';
  if (options) {
    options.forEach(o => {
      if (o.value === value) {
        displayValue = o.label;
      }
    })
  }

  return (
    <String value={displayValue} />
  )
}
