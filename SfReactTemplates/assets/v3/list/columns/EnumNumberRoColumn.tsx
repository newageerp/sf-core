import React, { Fragment } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import {String} from '@newageerp/data.table.base';
interface Props {
  fieldKey: string;
  options: any[];
}

export default function EnumNumberRoColumn(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];
  let displayValue = "";
  props.options.forEach(o => {
    if (o.value === value) {
      displayValue = o.label;
    }
  })

  return (
    <String value={displayValue} />
  )
}
