import { InputFloat, InputFloat4 } from '@newageerp/ui.form.base.form-pack';
import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';

interface Props {
  fieldKey: string;
  accuracy?: number;
}

export default function FloatEditableField(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element, updateElement } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];
  const updateValue = (e: any) => updateElement(props.fieldKey, e);

  if (props.accuracy === 4) {
    return <InputFloat4 className='tw3-w-40' value={value} onChangeFloat={updateValue} />;
  }

  return <InputFloat className='tw3-w-40' value={value} onChangeFloat={updateValue} />;
}
