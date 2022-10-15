import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';
import { Date } from '@newageerp/data.table.date'

interface Props {
  fieldKey: string;
}

export default function DateRoColumn(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];

  return (
    <Date value={value} />
  )
}
