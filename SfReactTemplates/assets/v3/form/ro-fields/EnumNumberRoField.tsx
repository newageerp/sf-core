import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';
import {Int} from '@newageerp/data.table.base';
interface Props {
  fieldKey: string;
  options: any[];
}

export default function EnumNumberRoField(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];
  let displayValue = 0;
  props.options.forEach(o => {
    if (o.value === value) {
      displayValue = o.label;
    }
  })

  return (
    <Int value={displayValue} />
  )
}
