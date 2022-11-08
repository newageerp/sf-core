import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';
import {FieldDateTime} from '@newageerp/v3.form.field-date-time'

interface Props {
  fieldKey: string;
}

export default function DateTimeEditableField(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element, updateElement } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];
  const updateValue = (e: any) => updateElement(props.fieldKey, e);

  return (
    <FieldDateTime
      value={value}
      onChange={updateValue}
      className='tw3-w-56'
    />
  )
}
