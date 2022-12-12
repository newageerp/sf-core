import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';
import { Float } from '@newageerp/data.table.base'

interface Props {
  fieldKey: string;
  accuracy?: number;
}

export default function FloatRoField(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];

  return (
    <Float value={value} accuracy={props.accuracy} />
  )
}
