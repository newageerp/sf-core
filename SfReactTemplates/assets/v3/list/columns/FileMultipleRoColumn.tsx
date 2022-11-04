import React, { Fragment } from 'react'
import OldFileFieldMultipleRo from '../../old-ui/OldFileFieldMultipleRo';
import { useTemplateLoader } from '../../templates/TemplateLoader';

interface Props {
  fieldKey: string;
}

export default function FileMultipleRoColumn(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const file = props.fieldKey in element ? element[props.fieldKey] : []

  if (!(!!file && file.length > 0)) {
    return <Fragment />;
  }

  return (
    <OldFileFieldMultipleRo files={file} />
  )
}
