import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';
import { FieldSelect } from '@newageerp/v3.bundles.form-bundle'

interface Props {
  fieldKey: string;
  options: any[];
}

export default function EnumTextEditableField(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element, updateElement } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];
  const updateValue = (e: any) => updateElement(props.fieldKey, e);

  return (
    <FieldSelect
      value={value}
      onChange={updateValue}
      options={props.options}
      className="tw3-w-full tw3-max-w-[500px]"
      icon='bars'
    />
  )
}
