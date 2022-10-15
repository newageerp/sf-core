import { UI } from '@newageerp/nae-react-ui';
import React, { Fragment } from 'react'
import { useDfValue } from '../../hooks/useDfValue';

interface Props {
  fieldKey: string;
  id: number;
}

export default function FileMultipleDfRoField(props: Props) {
  const value = useDfValue({ id: props.id, path: props.fieldKey });

  if (!value) {
    return <Fragment />;
  }

  return (
    <UI.Form.FileFieldMultipleRo files={value} />
  )
}
