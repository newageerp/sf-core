import React, { Fragment } from 'react'
import { useDfValue } from '../../hooks/useDfValue';
import OldFileFieldMultipleRo from '../../old-ui/OldFileFieldMultipleRo';

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
    <OldFileFieldMultipleRo files={value} />
  )
}
