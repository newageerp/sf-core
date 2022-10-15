import React, { Fragment } from 'react'
import { useTemplateLoader } from '../templates/TemplateLoader';
import FormFieldTagCloud from './FormFieldTagCloud';

interface Props {
  field: string,
  action: string,
}

export default function FormFieldTagCloudTemplate(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element, updateElement } = tData;

  if (!element) {
    return <Fragment />;
  }
  const val = element[props.field] ? element[props.field] : '';

  return <FormFieldTagCloud
    value={val}
    updateValue={(newVal) => updateElement(props.field, newVal)}
    action={props.action}
  />

}
