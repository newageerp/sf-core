import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';
import { Text } from '@newageerp/data.table.text';

interface Props {
  fieldKey: string;
}

export default function StringArrayRoColumn(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = props.fieldKey in element ? element[props.fieldKey].join("\n") : "";

  return (
    <Text value={value} />
  )
}
