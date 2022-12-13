import React, { Fragment } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { Datepicker } from "@newageerp/ui.form.base.form-pack";

interface Props {
  fieldKey: string;
}

export default function DateEditableField(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element, updateElement } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];
  const updateValue = (e: any) => updateElement(props.fieldKey, e);

  return <Datepicker className='tw3-w-40' value={value} onChange={updateValue} />;
}
