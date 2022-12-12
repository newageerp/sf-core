import React from 'react'
import { Text } from '@newageerp/data.table.base';
import { useDfValue } from '../../hooks/useDfValue';

interface Props {
  fieldKey: string;
  id: number;
  as?: string;
}

export default function LargeTextDfRoField(props: Props) {
  const value = useDfValue({ id: props.id, path: props.fieldKey });

  if (props.as === 'rich_editor') {
    return (
      <div>TODO</div>
    )
  } else {
    return (
      <Text
        value={value}
      />
    )
  }
}
