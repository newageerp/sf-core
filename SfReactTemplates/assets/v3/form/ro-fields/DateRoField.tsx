import React, { Fragment } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { Date } from '@newageerp/data.table.base'

interface Props {
  fieldKey: string;
}

export default function DateRoField(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];

  return (
    <Date value={value} format={process.env.REACT_APP_DATE_FORMAT} />
  )
}
