import React, { Fragment } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import {String} from '@newageerp/data.table.base';

interface Props {
  fieldKey: string;
}

export default function StringRoField(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];

  return (
    <String value={value} />
  )
}
