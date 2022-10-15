import React from 'react'
import { useDfValue } from '../../hooks/useDfValue';

interface Props {
  fieldKey: string;
  id: number;
}

export default function ImageDfRoField(props: Props) {
  const value = useDfValue({ id: props.id, path: props.fieldKey });

  return (
    <img src={value} className={'tw3-w-56'} />
  )
}
