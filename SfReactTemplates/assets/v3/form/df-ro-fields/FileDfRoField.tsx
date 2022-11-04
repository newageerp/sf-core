import React, { Fragment } from 'react'
import { useDfValue } from '../../hooks/useDfValue';
import OldFileFieldRo from '../../old-ui/OldFileFieldRo';

interface Props {
  fieldKey: string;
  id: number;
}

export default function FileDfRoField(props: Props) {
  const value = useDfValue({ id: props.id, path: props.fieldKey });

  if (!(value && 'filename' in value)) {
    return <Fragment />;
  }

  return (
    <OldFileFieldRo file={value} />
  )
}
