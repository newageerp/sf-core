import React, { Fragment } from 'react'
import OldFileFieldRo from '../../old-ui/OldFileFieldRo';
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';

interface Props {
  fieldKey: string;
}

export default function FileRoColumn(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];

  if (!(value && 'filename' in value)) {
    return <Fragment />;
  }

  return (
    <OldFileFieldRo file={value} short={true} />
  )
}
