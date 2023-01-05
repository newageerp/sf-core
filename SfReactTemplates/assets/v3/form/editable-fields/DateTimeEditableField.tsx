import React, { Fragment } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import {FieldDateTime} from '@newageerp/v3.bundles.form-bundle'

interface Props {
  fieldKey: string;
}

export default function DateTimeEditableField(props: Props) {
  const { data: tData } = useTemplatesLoader();
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
      containerClassName='tw3-w-96'
      className='tw3-w-56'
    />
  )
}
