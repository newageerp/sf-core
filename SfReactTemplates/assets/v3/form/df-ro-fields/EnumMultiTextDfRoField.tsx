import React from 'react'
import { useDfValue } from '../../hooks/useDfValue';
import { getPropertyForPath } from '../../utils';

interface Props {
  fieldKey: string;
  id: number;
  options?: any[];
}

export default function EnumMultiTextDfRoField(props: Props) {
  const value = useDfValue({ id: props.id, path: props.fieldKey });
  const prop = getPropertyForPath(props.fieldKey);
  const options = props.options?props.options:prop?.enum;
  
  return (
    <div>EnumMultiTextDfRoField</div>
  )
}
