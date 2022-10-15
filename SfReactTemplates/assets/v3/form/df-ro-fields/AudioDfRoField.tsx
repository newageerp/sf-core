import React from 'react'
import { useDfValue } from '../../hooks/useDfValue';

interface Props {
  fieldKey: string;
  id: number;
}

export default function AudioDfRoField(props: Props) {
  const value = useDfValue({ id: props.id, path: props.fieldKey });

  return (
    <audio controls>
      <source src={value} type='audio/mp3' />
      Your browser does not support the audio element.
    </audio>
  )
}
