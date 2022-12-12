import React, {Fragment} from 'react'
import { useDfValue } from '../../hooks/useDfValue';
import { Text } from '@newageerp/data.table.base';

interface Props {
  fieldKey: string;
  id: number,
}

export default function StringArrayDfRoField(props: Props) {
  const value = useDfValue({ id: props.id, path: props.fieldKey });

  if (!value) {
    return <Fragment />
  }

  return (
    <Text value={value.join("\n")} />
  )
}
