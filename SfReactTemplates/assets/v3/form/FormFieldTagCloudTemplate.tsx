import React, { Fragment } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import FormFieldTagCloud from './FormFieldTagCloud';

interface Props {
  field: string,
  action: string,
}

export default function FormFieldTagCloudTemplate(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element, updateElement } = tData;

  if (!element) {
    return <Fragment />;
  }
  const val = element[props.field] ? element[props.field] : '';

  return <FormFieldTagCloud
    element={element}
    value={val}
    updateValue={(newVal) => updateElement(props.field, newVal)}
    action={props.action}
  />

}
