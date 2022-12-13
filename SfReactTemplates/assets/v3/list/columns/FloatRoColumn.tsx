import React, { Fragment } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { Float } from '@newageerp/data.table.base'

interface Props {
  fieldKey: string;
  accuracy?: number;
}

export default function FloatRoColumn(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];

  return (
    <Float value={value} accuracy={props.accuracy}/>
  )
}
