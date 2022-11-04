import React, { Fragment } from 'react'
import OldFileFieldRo from '../../old-ui/OldFileFieldRo';
import { useTemplateLoader } from '../../templates/TemplateLoader';

interface Props {
  fieldKey: string;
}

export default function FileRoField(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];

  if (!(value && 'filename' in value)) {
    return <Fragment />;
  }

  return (
    <OldFileFieldRo file={value} />
  )
}
