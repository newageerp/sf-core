import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';
import {String} from '@newageerp/data.table.string';

interface Props {
  fieldKey: string;
  options: any[];
}

export default function EnumTextRoField(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];
  let displayValue = '';
  props.options.forEach(o => {
    if (o.value === value) {
      displayValue = o.label;
    }
  })

  return (
    <String value={displayValue} />
  )
}
