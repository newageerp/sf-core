import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';
import { Float } from '@newageerp/data.table.float'

interface Props {
  fieldKey: string;
}

export default function FloatRoColumn(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];

  return (
    <Float value={value} />
  )
}
