import React, { Fragment } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { Text } from '@newageerp/data.table.base';

interface Props {
  fieldKey: string;
}

export default function StringArrayRoField(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = props.fieldKey in element && Array.isArray(element[props.fieldKey]) ? element[props.fieldKey].join("\n") : "";

  return (
    <Text value={value} />
  )
}
