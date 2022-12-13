import React, { Fragment } from 'react'
import OldFileFieldMultipleRo from '../../old-ui/OldFileFieldMultipleRo';
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';

interface Props {
  fieldKey: string;
}

export default function FileMultipleRoColumn(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const file = props.fieldKey in element ? element[props.fieldKey] : []

  if (!(!!file && file.length > 0)) {
    return <Fragment />;
  }

  return (
    <OldFileFieldMultipleRo files={file} short={true} />
  )
}
