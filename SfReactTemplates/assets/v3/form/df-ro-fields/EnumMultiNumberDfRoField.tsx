import { functions } from '@newageerp/nae-react-ui';
import React from 'react'
import { useDfValue } from '../../hooks/useDfValue';

interface Props {
  fieldKey: string;
  id: number;
  options?: any[];
}

export default function EnumMultiNumberDfRoField(props: Props) {
  const value = useDfValue({ id: props.id, path: props.fieldKey });
  const prop = functions.properties.getPropertyForPath(props.fieldKey);
  const options = props.options?props.options:prop?.enum;

  return (
    <div>EnumMultiNumberDfRoField</div>
  )
}
