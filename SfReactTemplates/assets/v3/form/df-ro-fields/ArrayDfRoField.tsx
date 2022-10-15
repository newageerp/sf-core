import React from 'react'
import { useDfValue } from '../../hooks/useDfValue';

interface Props {
  fieldKey: string;
  id: number;
}

export default function ArrayDfRoField(props: Props) {
  const value = useDfValue({ id: props.id, path: props.fieldKey });

  return (
    <div>ArrayDfRoField</div>
  )
}
